<?php

        class Process
        {
                protected $children  = array();
                protected $is_parent = false;

                public function __construct()
                {
                }


                protected function hasActive()
                {
                        return count($this->children) > 0 && array_sum(array_values($this->children)) > 0;
                }

                public function activeCount()
                {
                        $count = 0;
                        foreach ($this->children as $pid => $xpid) {
                                if ($xpid > 0) {
                                        $count ++;
                                }
                        }
                        return $count;
		}

                public function create($cmd=false,$args=array(),$envs=array())
                {
                        $pid = pcntl_fork();
                        if ($pid > 0) {

				$this->is_parent = true;

                                if (!$cmd) {
                                        $this->children[$pid] = $pid;
                                }
                        } else {

				if ($cmd) {
                                        pcntl_exec($cmd, $args, $envs);
                                        exit;
                                }
                        }
                        return $pid;
                }

                public function wait($all=true,$hang=true)
                {
                        $status = 0;

                        if ($this->is_parent) {
                                while ($all && $this->hasActive()) {
                                        $pid = pcntl_wait($status, 0);
                                        $this->children[$pid] = 0;
                                }
                                if ($this->hasActive()) {
                                        $pid = pcntl_wait($status, ((!$hang) ? WNOHANG : 0));
                                        $this->children[$pid] = 0;
                                        return $pid;
                                }
                        }
                        return 0;
                }


        }


?>
