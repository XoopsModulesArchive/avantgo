<?php

require __DIR__ . '/admin_header.php';

$op = 'Choice';

if (isset($_POST['op'])) {
    $op = trim($_POST['op']);
} elseif (isset($_GET['op'])) {
    $op = trim($_GET['op']);
}

function Choice()
{
    global $xoopsModule;

    xoops_cp_header();

    OpenTable();

    echo "<a href='index.php?op=Config'>" . _AV_CONFIG . '</a><br>';

    CloseTable();

    xoops_cp_footer();
}

function Config()
{
    global $xoopsDB;

    xoops_cp_header();

    include '../cache/config.php';

    $topics_checked = explode(',', $cfgTopics);

    echo "<form name='avconfig' action='index.php?op=Save' method='post'>";

    echo "<table width='100%' class='outer' cellspacing='1'>";

    echo "<tr><th colspan='2'>Modul Konfiguration</th></tr>";

    echo "<tr valign='top' align='left'><td class='head'>" . _AV_TITLEINDEX . '</td>';

    echo "<td class='even'><input type='text' value='$cfgTitleIndex' name='cfgTitleIndex' size='50' maxlength='100'></td></tr>";

    echo "<tr valign='top' align='left'><td class='head'>" . _AV_IMAGEINDEX . '</td>';

    echo "<td class='even'><a href='javascript:xoopsCodeImg(\"cfgImageIndex\");'><img src='" . XOOPS_URL . "/images/imgsrc.gif' align='middle' alt='imgsrc'></a>";

    echo "&nbsp;<a href='javascript:openWithSelfMain(\"" . XOOPS_URL . "/imagemanager.php?target=cfgImageIndex\",\"imgmanager\",400,430);'><img src='" . XOOPS_URL . "/images/image.gif' align='middle' alt='image'></a><br>";

    echo "<input type='text' value='$cfgImageIndex' name='cfgImageIndex' size='50' maxlength='150'></td></tr>";

    echo "<tr valign='top' align='left'><td class='head'>" . _AV_TITLEITEM . '</td>';

    echo "<td class='even'><input type='text' value='$cfgTitleItem' name='cfgTitleItem' size='50' maxlength='100'></td></tr>";

    echo "<tr valign='top' align='left'><td class='head'>" . _AV_IMAGEITEM . '</td>';

    echo "<td class='even'><a href='javascript:xoopsCodeImg(\"cfgImageItem\");'><img src='" . XOOPS_URL . "/images/imgsrc.gif' align='middle' alt='imgsrc'></a>";

    echo "&nbsp;<a href='javascript:openWithSelfMain(\"" . XOOPS_URL . "/imagemanager.php?target=cfgImageItem\",\"imgmanager\",400,430);'><img src='" . XOOPS_URL . "/images/image.gif' align='middle' alt='image'></a><br>";

    echo "<input type='text' value='$cfgImageItem' name='cfgImageItem' size='50' maxlength='150'></td></tr>";

    echo "<tr valign='top' align='left'><td class='head'>" . _AV_COUNTER . '</td>';

    echo "<td class='even'><input type='text' value='$cfgCounter' name='cfgCounter' size='3' maxlength='5'></td></tr>";

    $sqlquery = $xoopsDB->query('SELECT topic_id, topic_title FROM ' . $xoopsDB->prefix('topics') . ' ORDER by topic_title');

    echo "<tr valign='top' align='left'><td class='head'>" . _AV_TOPIC . '</td>';

    echo "<td class='even'>";

    while (false !== ($sqlfetch = $xoopsDB->fetchArray($sqlquery))) {
        $myts = MyTextSanitizer::getInstance();

        $topic_id = $sqlfetch['topic_id'];

        $topic_title = $myts->displayTarea($sqlfetch['topic_title']);

        $topics_array = [$topic_id];

        if (in_array($topic_id, $topics_checked, true)) {
            $ischecked = 'checked';
        } else {
            $ischecked = '';
        }

        echo "<input type='checkbox' value='$topic_id' name='topics_array[]' " . $ischecked . '> ' . $topic_title . ' (ID: ' . $topic_id . ')<br>';
    }

    echo '</td></tr>';

    echo "<tr valign='top' align='left'><td class='head'>&nbsp;</td>";

    echo "<td class='even'><input type='submit' name='submit' value='" . _AV_SAVE . "'></td></tr>";

    echo '</table></form';

    xoops_cp_footer();
}

function Save($cfgCounter, $cfgTitleIndex, $cfgImageIndex, $cfgTitleItem, $cfgImageItem, $topics_array)
{
    global $xoopsDB;

    $cfgTopics = '';

    while (list($id, $topic_id) = each($topics_array)) {
        if ('' != $cfgTopics) {
            $cfgTopics .= ',';
        }

        $cfgTopics .= $topic_id;
    }

    $fp = fopen('../cache/config.php', 'wb');

    fwrite(
        $fp,
        '<?php
$cfgCounter=' . $cfgCounter . ';
$cfgTitleIndex=\'' . $cfgTitleIndex . '\';
$cfgImageIndex=\'' . $cfgImageIndex . '\';
$cfgTitleItem=\'' . $cfgTitleItem . '\';
$cfgImageItem=\'' . $cfgImageItem . '\';
$cfgTopics=\'' . $cfgTopics . '\';
?>'
    );

    fclose($fp);

    redirect_header('index.php', 1, _AV_MODIFSAVE);
}

switch ($op) {
    case 'Config':
        Config();
        break;
    case 'Save':
        Save($cfgCounter, $_POST['cfgTitleIndex'], $_POST['cfgImageIndex'], $_POST['cfgTitleItem'], $_POST['cfgImageItem'], $_POST['topics_array']);
        break;
    default:
        Choice();
        break;
}
