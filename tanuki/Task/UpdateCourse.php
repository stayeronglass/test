<?php
namespace tanuki;

use tanuki\LockableTrait;

/**
 * Class UpdateCourse
 *
 * Таск получающий данные и обновляющий и БД и кэш.
 */
class UpdateCourse {

    use LockableTrait;

    public function run(){

        if (!$this->lock()) {
            throw new CourseException('The command is already running in another process!');
        }

        try {
            $course = $this->getHttpData();

            $this
                ->updateDb($course)
                ->updateCache($course)
            ;

        } catch (\Exception $e){
            //логировать
        }

        return 0;
    }

    private function getHttpData(): ?Course {

        return null;
    }


    private function updateDb() : self
    {
        //.....................//
        return $this;
    }


    private function updateCache() : self
    {
        //.....................//
        return $this;
    }
}



$task = new UpdateCourse();

$task->run();