<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $attachable_type
 * @property int $attachable_id
 * @property string $file_location
 * @property int $file_size
 * @property string $preview_location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $attachable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereAttachableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereAttachableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereFileLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment wherePreviewLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment withoutTrashed()
 */
	class Attachment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $office_id
 * @property int|null $biometric_device_status_id
 * @property string $name
 * @property string $serial
 * @property string|null $model
 * @property int $user_count
 * @property int $fingerprint_count
 * @property string $ip_address
 * @property int $port
 * @property string $status_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereBiometricDeviceStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereFingerprintCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereStatusAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDevice whereUserCount($value)
 */
	class BiometricDevice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDeviceStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDeviceStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDeviceStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDeviceStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDeviceStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDeviceStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BiometricDeviceStatus whereUpdatedAt($value)
 */
	class BiometricDeviceStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\Employee|null $employee
 * @property-read \App\Models\User|null $verified_user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Check withoutTrashed()
 */
	class Check extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CheckStatus query()
 */
	class CheckStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|College newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|College newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|College query()
 */
	class College extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id employee_no
 * @property int|null $office_id
 * @property int|null $employment_type_id
 * @property string|null $full_name
 * @property string|null $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Check> $checks
 * @property-read int|null $checks_count
 * @property-read \App\Models\College|null $college
 * @property-read \App\Models\Office|null $office
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmploymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedAt($value)
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmploymentType whereUpdatedAt($value)
 */
	class EmploymentType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array<array-key, mixed> $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model $notifiable
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification read()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification withoutTrashed()
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Office whereUpdatedAt($value)
 */
	class Office extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $employee_id
 * @property int $biometric_device_id
 * @property int $report_type_id
 * @property string $description
 * @property string|null $action_taken
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereActionTaken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereBiometricDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereReportTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report whereUpdatedAt($value)
 */
	class Report extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType whereUpdatedAt($value)
 */
	class ReportType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

