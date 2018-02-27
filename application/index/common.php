<?php
function outBadge($data)
{
    $value = null;
    if($data['tops'] == 1)
    {
        $value = ' <span class="mf-badge mf-badge-danger mf-radius">置顶</span>';
    }
    if($data['essence'] == 1)
    {
        $value = $value.'<span class="mf-badge mf-badge-warning mf-radius">精华</span>';
    }

    return $value;
}

                