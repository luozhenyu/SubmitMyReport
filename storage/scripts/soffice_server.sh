#!/bin/bash

nohup /usr/bin/soffice --accept="socket,host=127.0.0.1,port=2002;urp;" --headless --nofirststartwizard >/dev/null 2>&1 &