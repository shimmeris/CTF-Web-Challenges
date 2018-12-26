from django.db import models
from django.contrib.auth.models import User
from django.dispatch import receiver
from django.db.models.signals import post_save
from django.core import validators
from django import forms
from captcha.fields import CaptchaField


class UserProfile(models.Model):
    user = models.OneToOneField(User, related_name='profile', on_delete=models.CASCADE)
    token = models.TextField()
    url = models.TextField(default='https://shiamotivate.me')
    created_at = models.DateTimeField(auto_now_add=True)

    @receiver(post_save, sender=User)
    def create_user_profile(sender, instance, created, **kwargs):
        if created:
            UserProfile.objects.create(user=instance)

    @receiver(post_save, sender=User)
    def save_user_profile(sender, instance, **kwargs):
        instance.profile.save()   

class Feedback(models.Model):
    url = models.TextField()
    visited = models.BooleanField(default=False)
    duration = models.IntegerField(default=5)
    created_at = models.DateTimeField(auto_now_add=True)
    from captcha.fields import CaptchaField

class CaptchaTestForm(forms.Form):
    captcha = CaptchaField()

