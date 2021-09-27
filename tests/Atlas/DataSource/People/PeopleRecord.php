<?php
declare(strict_types=1);

namespace Circli\Database\Tests\Atlas\DataSource\People;

use Atlas\Mapper\Record;

/**
 * @method PeopleRow getRow()
 */
class PeopleRecord extends Record
{
    use PeopleFields;
}
