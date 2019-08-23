#!/usr/bin/bash

if [ ! -d "./logs" ]; then
  mkdir "./logs"
fi

DT=$(date "+%d_%m_%Y")
nohup ./rr serve -v -d -l plain -o env.ENVIRONMENT="development" -o http.workers.pool.numWorkers=1 -o http.workers.pool.maxJobs=1 >"logs/stdout_${DT}.log" 2>"logs/stderr_${DT}.log" &
