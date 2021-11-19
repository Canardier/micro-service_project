import flask
from flask import request
from mailer import sendmailRegistered

app = flask.Flask(__name__)
app.config["DEBUG"] = True


@app.route('/', methods=['GET'])
def home():
    return {
        "code": 200,
        "data": "we are there / on this api"
    }

@app.route('/send', methods=['POST'])
def home2():
    data = sendmailRegistered('ok')
    return {
        "code": 200,
        "data": data
    }


app.run(host="0.0.0.0", port=int("5000"), debug=True)
