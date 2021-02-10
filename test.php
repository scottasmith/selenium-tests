<?php

function shutdown(){
    echo "\033c";                                        // Clear terminal
    system("tput cnorm && tput cup 0 0 && stty echo");   // Restore cursor default
    echo PHP_EOL;                                        // New line
    exit;                                                // Clean quit
}

register_shutdown_function("shutdown");                  // Handle END of script

pcntl_signal(SIGINT,"shutdown");                         // Catch SIGINT, run shutdown()

sleep(100);
