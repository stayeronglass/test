<?php
namespace tanuki;

use tanuki\Course;

class CourseManager implements CourseManagerInterface
{


    /**
     * @return Course|null
     *
     * получимть данные из кэша,  если их там нет - получить данные из БД.
     * Если и там нет - запустить таск который получает данные из внешних источников, и честно сказать что данных у нас пока нет.
     */
    public function getCourse() : ?Course
    {
        try {

            if (null !== ($result = $this->getFromCache())) {
                return $result;
            } elseif (null !== ($result = $this->getFromDb())) {// может создать нагрузку на БД, в этом случае оставить только кэш
                return $result;
            } else {
                $this->runUpdate();
            }

        } catch (\Exception $e){
            //логировать
        }

        return null;
    }

    /**
     * @return Course|null
     */
    private function getFromCache(): ?Course {}

    /**
     * @return Course|null
     */
    private function getFromDb(): ?Course{}


    private function runUpdate()
    {
        chdir(dirname(__FILE__) . '/Task');
        system('php UpdateCourse.php &');
    }
}