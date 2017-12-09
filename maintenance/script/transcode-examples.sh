#!/bin/bash
set -eu
# This script converts new .ogg recordings to .mp3
# Between these two formats, all normal browsers are supported.

# Check LAME exists
ffmpeg="ffmpeg"
cmd=$(type -P $ffmpeg)
if [ -z "$cmd" ]; then
	echo "Install ffmpeg to use this script (command $ffmpeg not found) "
	exit 1
fi

# Move to OGG directory for words
cd ../../data/audio/spelling/ogg
mkdir -p ../mp3/
for name in *.ogg; do
	# Modified form of http://linuxforums.org.uk/index.php?topic=9836.0
	dest="../mp3/${name/.ogg/.mp3}"
	if [ ! -f $dest ]; then
		# Only transcode files that don't exist
		ffmpeg -i "$name" -ab 128k -map_metadata 0:0,s0 "$dest";
	fi
done;

# Repeat for examples
cd ../../example/ogg
mkdir -p ../mp3/
for name in *.ogg; do
	# Modified form of http://linuxforums.org.uk/index.php?topic=9836.0
	dest="../mp3/${name/.ogg/.mp3}"
	if [ ! -f $dest ]; then
		# Only transcode files that don't exist
		ffmpeg -i "$name" -ab 128k -map_metadata 0:0,s0 "$dest";
	fi
done;
