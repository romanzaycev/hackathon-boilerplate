#!/usr/bin/bash

if [ ! -d "./logs" ]; then
  mkdir "./logs"
fi

DT=$(date "+%d_%m_%Y")
nohup ./rr serve -v -d -l plain >"logs/stdout_${DT}.log" 2>"logs/stderr_${DT}.log" &
