<?php


namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait CrudGenerator
{
    /**
     * Create new record.
     *
     * @param array $data
     * @return $this
     */
    public function createRecord(array $data)
    {
        if (is_array($data) && $data) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
            $this->save();
        }
        return $this;
    }

    /**
     * Update record.
     *
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateRecord($id, array $data = [])
    {
        $info = $this->find($id);
        $changed = null;

        if (is_array($data) && $data) {
            foreach ($data as $key => $value) {
                $info->$key = $value;
            }
            $info->save();
        }
        return $info;
    }

    public function updateMyRecord(array $data = [])
    {
        @$this->updated_at = date('Y-m-d H:i:s');
        @$this->updated_by = @Auth::id();

        if (is_array($data) && $data) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
            $this->save();
        }
        return $this;
    }
}