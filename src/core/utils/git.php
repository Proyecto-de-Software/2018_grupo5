<?php

function last_commit() {
    return exec("git log -1b--pretty=format:Last commit %ar");
}
