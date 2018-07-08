import MySQLdb
import time
import os
import signal
import requests
from subprocess import check_output, Popen


def login():
    url = 'http://127.0.0.1/'
    user = 'admin'
    pw = 'JJOIUQjouwqhirhq329pP!!!1sdqqweck1'

    s = requests.Session()
    s.get(url)
    token = s.cookies['csrftoken']
    s.post(url, data=dict(
        username=user,
        password=pw,
        csrfmiddlewaretoken=token))
    
    # reset admin urls
    s.post(url + 'urlstorage', data=dict(
        url='https://shiamotivate.me'))
    return s.cookies['sessionid']

db = MySQLdb.connect(user='xss', password='shiaisthebest', db='urlstorage')
c = db.cursor()

while True:
    c.execute('COMMIT')
    c.execute('SELECT id, url, duration FROM urlstorage_feedback WHERE not visited ORDER BY created_at ASC LIMIT 1')
    feedback = c.fetchone()

    if feedback is None:
        time.sleep(1)
        continue
    id, url, duration = feedback

    c.execute('COMMIT')
    c.execute('UPDATE urlstorage_feedback SET visited = 1 WHERE id = %s', (id,))
    c.execute('COMMIT')

    if not url.startswith('http://') or url.startswith('https://'):
        print('Invalid scheme for URL {}'.format(url))
        continue

    print('Processing URL {}'.format(url))
    sessionid = login()
    p = Popen(['phantomjs', '/xss/visit.js', url, sessionid, str(duration)], preexec_fn=os.setsid)
    pgid = os.getpgid(p.pid)

    try:
        p.communicate()
    except:
        print('p.communicate failed')

    try:
        os.killpg(pgid, signal.SIGKILL)
    except:
        print('os.killpg failed')

    print('Done processing URL {}'.format(url))


