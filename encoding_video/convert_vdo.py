# ffmpeg -i vdo.mp4 -vf scale=-1:480 -vcodec mpeg4 -qscale 3 ./output.mp4
# ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=s=x:p=0 myfile.mp4 | cut -d 'x' -f 2
# 1080, 720, 480, 360, 240
import subprocess
from logging import log
import os
import ffmpeg
import sys

def my_converter(mydata):
    exist_format = ("1080", "720", "480", "360", "240")
    i = 0
    my_format = str(mydata['format'])
    debug = []

    if my_format == '240':
        return "basic format is 240"
    
    formated_name = ''.join(mydata['vdo_name'].split('.')[:-1])
    while i < len(exist_format) -1:
        if my_format == exist_format[i]:
            while i < len(exist_format) - 1:
                new_name = formated_name + "_" + exist_format[i + 1] + ".mp4"
                cmd = "ffmpeg -i {} -vf scale=-1:{} -vcodec mpeg4 -qscale 3 {}".format(mydata['path'] + mydata['vdo_name'], exist_format[i + 1], mydata['path'] + new_name)
                debug.append(cmd)
                i += 1
                vdo = subprocess.Popen(cmd, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        i += 1
    return debug

def my_getformat(my_data):
    exist_format = ("1080", "720", "480", "360", "240")
    i = 0
    path = './../storage/' + my_data[0]['source'].split('/')[4] + "/"
    name = my_data[0]['source'].split('/')[5]

    probe = ffmpeg.probe(path + name)
    video_stream = next((stream for stream in probe['streams'] if stream['codec_type'] == 'video'), None)
    width = int(video_stream['width'])
    height = int(video_stream['height'])

    data = {
        'path': path,
        'vdo_name': name,
        'format': video_stream['coded_height'],
    }
    return data