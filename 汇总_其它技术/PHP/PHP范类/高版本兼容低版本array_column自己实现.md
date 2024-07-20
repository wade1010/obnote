if (!function_exists("array_column")) {

    function array_column($input, $columnKey, $indexKey = null)

    {

        $columnKeyIsNumber = (is_numeric($columnKey)) ? true : false;

        $indexKeyIsNull = (is_null($indexKey)) ? true : false;

        $indexKeyIsNumber = (is_numeric($indexKey)) ? true : false;

        $result = array();

        foreach ((array)$input as $key => $row) {

            if ($columnKeyIsNumber) {

                $tmp = array_slice($row, $columnKey, 1);

                $tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : null;

            } else {

                $tmp = isset($row[$columnKey]) ? $row[$columnKey] : null;

            }

            if (!$indexKeyIsNull) {

                if ($indexKeyIsNumber) {

                    $key = array_slice($row, $indexKey, 1);

                    $key = (is_array($key) && !empty($key)) ? current($key) : null;

                    $key = is_null($key) ? 0 : $key;

                } else {

                    $key = isset($row[$indexKey]) ? $row[$indexKey] : 0;

                }

            }

            $result[$key] = $tmp;

        }

        return $result;

    }

}