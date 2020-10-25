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
include '../../class/pagenav.php';
include 'cache/config.php';

//count number of news
$sqlquery = $xoopsDB->query('SELECT count(*) as ncount FROM ' . $xoopsDB->prefix('stories') . ' WHERE published>0 AND published<=' . time() . ' AND expired <= ' . time() . ' AND topicid in(' . $cfgTopics . ')');
$sqlfetch = $xoopsDB->fetchArray($sqlquery);
$nnews = $sqlfetch['ncount'];

if (!isset($limite)) {
    $limite = 0;
}

$pagenav = new XoopsPageNav($nnews, $cfgCounter, $limite, 'limite', '');

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
echo '<html><head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _CHARSET . '">';
echo '<title>' . $xoopsConfig['sitename'] . '</title>';
echo '<meta name="AUTHOR" content="' . $xoopsConfig['sitename'] . '">';
echo '<meta name="COPYRIGHT" content="Copyright (c) 2003 by ' . $xoopsConfig['sitename'] . '">';
echo '<meta name="DESCRIPTION" content="' . $xoopsConfig['slogan'] . '">';
echo '<meta name="GENERATOR" content="' . XOOPS_VERSION . '">';
echo '</head>';
echo '<body bgcolor="#ffffff" text="#000000">';

if ('' != $cfgImageIndex) {
    $cfgImageIndex = $myts->displayTarea($cfgImageIndex, 1, 1, 1);
}
echo "<table border='0' width='100%'>";
echo "<tr><td align='center'>" . $cfgImageIndex . '</td></tr>';
echo '</table>';

if ('' != $cfgTitleIndex) {
    echo '<h3 align="center">' . $cfgTitleIndex . '</h3>';
}

echo '  <table border=0 align=center>';
echo ' 		<tr>';
echo '			<td bgcolor=#EFEFEF>' . _AV_ARTICLES . '</td><td bgcolor=#EFEFEF>' . _AV_DATE . '</td>';
echo '		</tr>';

$sqlquery = $xoopsDB->query('SELECT storyid, title, published FROM ' . $xoopsDB->prefix('stories') . ' WHERE published>0 AND published<=' . time() . ' AND expired <= ' . time() . ' AND topicid in(' . $cfgTopics . ') ORDER BY published DESC limit ' . (int)$limite . ',' . $cfgCounter);

$nnews -= $limite;
while (false !== ($sqlfetch = $xoopsDB->fetchArray($sqlquery))) {
    $myts = MyTextSanitizer::getInstance();

    $storyid = $myts->displayTarea($sqlfetch['storyid']);

    $title = $myts->displayTarea($sqlfetch['title']);

    $published = formatTimestamp($sqlfetch['published']);

    echo ' 		<tr>';

    echo '			<td><a href=item.php?storyid=' . $storyid . '>' . $title . '</a></td><td>' . $published . '</td>';

    echo '		</tr>';
}
echo "<tr><td>&nbsp</td><td align='right'>" . $pagenav->renderNav() . '</td></tr>';
echo '	</table>';
echo '</div>';
echo '</body>';
echo '</html>';
