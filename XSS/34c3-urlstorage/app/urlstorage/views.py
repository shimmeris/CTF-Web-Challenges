import os
import random
import string
import hashlib
import binascii
import struct

import django.contrib.auth.views as auth_views
from django.core.validators import URLValidator
from django.core.exceptions import ValidationError
from django.db import transaction
from django.http import HttpResponse, HttpResponseServerError
from django.contrib.auth.models import User
from django.contrib.auth.decorators import login_required
from django.shortcuts import redirect, render
from django.contrib import auth
from django.contrib.auth.forms import UserCreationForm, AuthenticationForm
from django.contrib import messages
from django.views.decorators.csrf import csrf_exempt

from .models import *

PROOF_OF_WORK_HARDNESS = 13371337

def proof_of_work_okay(chall, solution):
    hardness, chall = chall.split("_")
    h = hashlib.sha256(chall.encode('ASCII') + struct.pack('<Q', solution)).hexdigest()
    return int(h, 16) < 2**256 / int(hardness)

def random_string(length = 10):
    characters = string.ascii_letters + string.digits
    return ''.join(random.choice(characters) for _ in range(length))

def notfound(req):
    return redirect('index')

@login_required
def contact(req):
    if req.method == 'POST':
        form = CaptchaTestForm(req.POST)
        if not form.is_valid():
            return HttpResponseServerError("invalid captcha")
            
        url = req.POST.get('url')
        task = req.session.get('pow')
        sol = req.POST.get('pow')
        duration = 3 
        if task and sol:
            try:
                sol = int(sol)
            except:
                return HttpResponseServerError("proof of work solution should be a number")

            if not proof_of_work_okay(task, sol):
                messages.add_message(req, messages.ERROR, 
                    'Proof of work wrong! Solve it via ./pow.py {}'.format(req.session['pow']))
                return redirect('contact')
            else:
                duration = 30


        req.session['pow'] = str(PROOF_OF_WORK_HARDNESS) + "_" + random_string()

        if url:
            try:
                URLValidator()(url)
                Feedback.objects.create(url=url, duration=duration).save()
                messages.add_message(req, messages.INFO,
                    'The admin is going to look at your report shortly ( \
                    he will spend {} seconds looking at it)! Check the current \
                    queue status at /contact/queue.'.format(duration))
            except ValidationError as e:
                messages.add_message(req, messages.ERROR, 'invalid url')
                return redirect('contact')
        return redirect('index')

    if not req.session.get('pow'):
        req.session['pow'] = str(PROOF_OF_WORK_HARDNESS) + "_" + random_string()

    return render(req, 'contact.html', dict(
        pow=req.session['pow'], 
        captcha=CaptchaTestForm()))

@login_required
def contact_queue(req):
    cnt = Feedback.objects.filter(visited=0).count()
    return HttpResponse("Currently there are <b>{}</b> URLs in the queue for the admin.".format(cnt))

@login_required
def logout(req):
    auth.logout(req)
    return redirect("index")


@login_required
def flag(req):
    user_token = req.GET.get("token")
    if not user_token:
        messages.add_message(req, messages.ERROR, 'no token provided')
        return redirect('index')
    user_flag = "34C3_"+hashlib.sha1("foqweqdzq%s".format(user_token).encode("utf-8")).hexdigest()
    return render(req, 'flag.html', dict(user=req.user, 
        valid_token=user_token.startswith(req.user.profile.token), 
        user_flag=user_flag,
        user_token=user_token[:64],))

def login(req):
    if req.user.is_authenticated:
        return redirect('index')

    if req.method == "POST":
        username = req.POST.get("username")
        password = req.POST.get("password")
        if not username or not password:
            messages.add_message(req, messages.ERROR, 'No username/password provided')
        elif len(password) < 8:
            messages.add_message(req, messages.ERROR, 'Password length min 8.')
        else:
            user, created = User.objects.get_or_create(username=username)
            if created:
                user.set_password(password)
                user.save()
            user = auth.authenticate(username=username, password=password)
            if user:
                user.profile.token = binascii.hexlify(os.urandom(16)).decode()
                user.save()
                auth.login(req, user)
                return redirect('index')
            else:
                messages.add_message(req, messages.ERROR, 'Invalid password')

            return render(req, 'login.html')
    return render(req, 'login.html')

@login_required
@csrf_exempt
def index(req):
    if req.method == 'POST':
        url = req.POST.get('url')
        if url:
            req.user.profile.url = url
            req.user.save()

    return render(req, 'index.html', dict(user=req.user))
