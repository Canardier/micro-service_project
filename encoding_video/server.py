import flask
from flask import request
from convert_vdo import my_getformat, my_converter
import sys

app = flask.Flask(__name__)
app.config["DEBUG"] = True


@app.route('/', methods=['GET'])
def home():
    return {
        "code": 200,
        "data": "we are there / on this api"
    }

@app.route('/convert', methods=['POST'])
def home2():
    vdo_info = my_getformat(request.get_json())
    data = my_converter(vdo_info)
    return {
        "code": 200,
        "data": data
    }


app.run(host="0.0.0.0", port=int("5000"), debug=True)
