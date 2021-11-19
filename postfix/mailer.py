from logging import log
from smtplib import SMTP


def sendmailRegistered(email):

    sender = "no-reply@my_youtube.com"

    message_template = ("""From: My Youtube <no-reply@my_youtube.com>
    To: <pub@liosfr.net>
    Subject: Hello
    This is a test""")

    with SMTP("localhost", 25) as smtp:
        smtp.sendmail(sender, email, message_template.format(sender, email))


    return 'ok'
