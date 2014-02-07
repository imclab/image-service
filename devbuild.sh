#!/bin/sh
phing devbuild -f "$(dirname "$0")"/build.xml -Dws "$(dirname "$0")"
