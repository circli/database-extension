<?php
declare(strict_types=1);

namespace Circli\Database\Tests\Atlas\DataSource\People;

use Atlas\Mapper\RecordSet;

/**
 * @method PeopleRecord offsetGet($offset)
 * @method PeopleRecord appendNew(array $fields = [])
 * @method PeopleRecord|null getOneBy(array $whereEquals)
 * @method PeopleRecordSet getAllBy(array $whereEquals)
 * @method PeopleRecord|null detachOneBy(array $whereEquals)
 * @method PeopleRecordSet detachAllBy(array $whereEquals)
 * @method PeopleRecordSet detachAll()
 * @method PeopleRecordSet detachDeleted()
 */
class PeopleRecordSet extends RecordSet
{
}
