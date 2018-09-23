<?php

function last_commit() {
    return exec('git log -1 --format="Ultima actualización %ar"');
}
