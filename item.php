<?php

// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //

require dirname(__DIR__, 2) . '/mainfile.php';
if (empty($storyid)) {
    redirect_header('index.php');
} else {
    $storyid = (int)$storyid;
}
require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
include 'cache/config.php';

global $xoopsConfig, $xoopsModule;
$story = new NewsStory($storyid);
$datetime = formatTimestamp($story->published());
$myts = MyTextSanitizer::getInstance();
$storyhometext = $myts->displayTarea($story->hometext(), 1, 0, 0);
$storybodytext = $myts->displayTarea($story->bodytext(), 1, 0, 0);

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
echo '<html><head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _CHARSET . '">';
echo '<title>' . $xoopsConfig['sitename'] . '</title>';
echo '<meta name="AUTHOR" content="' . $xoopsConfig['sitename'] . '">';
echo '<meta name="COPYRIGHT" content="Copyright (c) 2003 by ' . $xoopsConfig['sitename'] . '">';
echo '<meta name="DESCRIPTION" content="' . $xoopsConfig['slogan'] . '">';
echo '<meta name="GENERATOR" content="' . XOOPS_VERSION . '">';
echo '<body bgcolor="#ffffff" text="#000000">';

if ('' != $cfgImageItem) {
    $cfgImageItem = $myts->displayTarea($cfgImageItem, 1, 1, 1);
}
echo "<table border='0' width='100%'>";
echo "<tr><td align='center'>" . $cfgImageItem . '</td></tr>';
echo '</table>';

if ('' != $cfgTitleIndex) {
    echo '<h3 align="center">' . $cfgTitleIndex . '</h3>';
}

echo '<table border="0" align="center"><tr><td align="center">
<table bgcolor="#ffffff"><tr><td align="center">	
<h3>' . $story->title() . '</h3>
<small><b>' . _AV_DATE . '</b>&nbsp;' . $datetime . '<br><b>' . _AV_TOPICC . '</b>&nbsp;' . $story->topic_title() . '</small><br><br></td></tr>';
echo '<tr><td align="center">' . $storyhometext . '<br><br>';
if ('' != $storybodytext) {
    echo $storybodytext . '<br><br>';
}
echo '</td></tr></table>
<br><br>';
printf(_AV_THISCOMESFROM, $xoopsConfig['sitename']);
echo '<br><a href="' . XOOPS_URL . '/">' . XOOPS_URL . '</a><br><br>
' . _AV_URLFORSTORY . '<br>
<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/article.php?storyid=' . $story->storyid() . '">' . XOOPS_URL . '/article.php?storyid=' . $story->storyid() . '</a>
</td></tr></table>
</body>
</html>';
