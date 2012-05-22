#!/bin/bash

CHROOT_PATH = "/tmp/chroot_$1"

mkdir $CHROOT_PATH
mkdir "$CHROOT_PATH/sys"
mkdir "$CHROOT_PATH/proc"

makejail "/tmp/chroot_conf_$1"

cp -rfv /usr/share/php "$CHROOT_PATH/usr/share/"
chown -R www-data:www-data $CHROOT_PATH

mount -o bind "$CHROOT_PATH/sys" /sys
mount -o bind "$CHROOT_PATH/proc" /proc
