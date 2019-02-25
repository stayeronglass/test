<?php
namespace tanuki;

use tanuki\Course;

/**
 * Interface CourseManagerInterface
 *
 * интерфейс который мы отдаем наружу всем желающим
 */
interface CourseManagerInterface
{
    public function getCourse() : ?Course;
}