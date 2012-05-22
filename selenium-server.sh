#!/bin/bash
sudo -u selenium "DISPLAY=\":99\" nohup java -jar /home/selenium/selenium.jar > /home/selenium/selenium.log &"
