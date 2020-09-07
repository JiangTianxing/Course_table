<?php

function getCourses()
{
//      'name', 'start_week', 'end_week', 'start_time', 'end_time', 'teacher', 'room', 'description'
    $data   = [
        '数值分析', 1, 1, 10, 3, 4, '钟尔杰', '深高研院507', '',
        '机器学习', 1, 1, 8, 5, 6, '田玲、张栗粽', '深高研院507', '',
        '并行算法(2班)', 1, 13, 16, 5, 7, '段贵多', '深高研院507', '',
        '嵌入式系统设计', 1, 11, 18, 1, 2, '罗蕾、陈虹', '深高研院507', '',
        'GPU并行编程', 2, 13, 16, 3, 4, '卢国明', '深高研院506', '',
        '工程伦理与学术道德(2班)', 2, 11, 15, 9, 10, '陈光宇', '深高研院506', '',
        '软件安全性分析', 2, 1, 8, 9, 11, '陈厅', '深高研院506', '',
        '数值分析', 3, 1, 10, 3, 4, '钟尔杰', '深高研院507', '',
        '并行算法(2班)', 3, 13, 16, 5, 6, '段贵多', '深高研院507', '',
        '中国特色社会主义理论与实践(21班)', 3, 13, 18, 9, 11, '叶本乾', '深高研院507', '',
        '机器学习', 3, 1, 8, 9, 11, '田玲、张栗粽', '深高研院507', '',
        '中国特色社会主义理论与实践(21班)', 3, 13, 18, 9, 11, '叶本乾', '深高研院507', '',
        '工程伦理与学术道德(2班)', 4, 11, 15, 3, 4, '陈光宇', '深高研院506', '',
        '软件安全性分析', 4, 1, 8, 3, 4, '陈厅', '深高研院506', '',
        'GPU并行编程', 4, 13, 16, 5, 7, '卢国明', '深高研院506', '',
        '中国特色社会主义理论与实践(21班)', 4, 13, 18, 9, 11, '叶本乾', '深高研院507', '',
        '数值分析', 5, 1, 10, 5, 6, '钟尔杰', '深高研院507', '',
        '图论及应用', 5, 1, 10, 7, 8, '杨春', '深高研院507', '',
        '图论及应用', 6, 1, 10, 1, 4, '杨春', '深高研院507', '',
    ];
    $len    = count($data);
    $result = [];
    for ($i = 0; $i < $len; $i += 9) {
        $result[] = [
            'name'        => $data[$i],
            'weekday'     => $data[$i + 1],
            'start_week'  => $data[$i + 2],
            'end_week'    => $data[$i + 3],
            'start_time'  => $data[$i + 4],
            'end_time'    => $data[$i + 5],
            'teacher'     => $data[$i + 6],
            'room'        => $data[$i + 7],
            'description' => $data[$i + 8],
        ];
    }
    return $result;
}

function getTimeMap()
{
    return [
        1  => ['0030', '0115'],
        2  => ['0120', '0205'],
        3  => ['0220', '0305'],
        4  => ['0310', '0355'],
        5  => ['0630', '0715'],
        6  => ['0720', '0805'],
        7  => ['0820', '0905'],
        8  => ['0910', '0955'],
        9  => ['1130', '1215'],
        10 => ['1220', '1305'],
        11 => ['1310', '1355']
    ];
}

$courses  = getCourses();
$time_map = getTimeMap();
$body     = '';
$start    = 1599440961;
$current  = '20200907T122200Z';
foreach ($courses as $course) {
    for ($i = $course['start_week']; $i <= $course['end_week']; $i++) {
        $days       = ($i - 1) * 7 + ($course['weekday'] - 1);
        $start_time = date('Ymd', $start + 24 * 60 * 60 * $days) . 'T' . $time_map[$course['start_time']][0] . '00Z';
        $end_time   = date('Ymd', $start + 24 * 60 * 60 * $days) . 'T' . $time_map[$course['end_time']][1] . '00Z';
        $body       .= "BEGIN:VEVENT
DTSTART:{$start_time}
DTEND:{$end_time}
DTSTAMP:{$current}
UID:{$start_time}
DESCRIPTION:电子科技大学(深圳)高等研究院2020级计算机学院课表
LOCATION:{$course['room']}
SUMMARY:{$course['name']}-{$course['teacher']}
END:VEVENT
";
    }
}
$template = "BEGIN:VCALENDAR
PRODID:-//Jiang Tianxing//电子科技大学深圳高等研究院计算机课表//CN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:蒋天星的课表
X-WR-TIMEZONE:Asia/Shanghai
{$body}END:VCALENDAR
";
header('Content-type:text/calendar; charset=utf-8');
header('Content-Disposition:attachment;filename="蒋天星的课表.ics"');
echo $template;