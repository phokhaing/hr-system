<?php

namespace Modules\Payroll\Traits;

trait CRUDable
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

            $user       = auth()->user();

            @$this->created_by = @$user->id;
            @$this->updated_by = @$user->id;
            @$this->created_at = date('Y-m-d H:i:s');
            @$this->updated_at = date('Y-m-d H:i:s');

            // @$this->created_at = date('Y-m-d H:i:s', strtotime('last month'));
            // @$this->updated_at = date('Y-m-d H:i:s', strtotime('last month'));

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
        $user       = auth()->user();

        $info    = $this->find($id);
        $changed = null;

        @$info->updated_at = date('Y-m-d H:i:s');
        @$info->updated_by = @$user->id;

        if (is_array($data) && $data) {
            foreach ($data as $key => $value) {
                $info->$key = $value;
            }
            $info->save();
        }
        return $info;
    }
}