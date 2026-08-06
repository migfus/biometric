import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import { TopNavigation } from './globalInterfaces'

const $page = usePage()

export const CTopNavigation: TopNavigation[] = [
    {
        name: 'Submit Report',
        icon: 'material-symbols:report-outline',
        href: route('reports.create'),
        components: ['reports/create'],
    },
    // {
    //     name: 'Camera',
    //     icon: 'mdi:camera-outline',
    //     href: route('camera.index'),
    //     components: ['index'],
    // },
    // {
    //     name: 'Records',
    //     icon: 'material-symbols:list',
    //     href: route('records.index'),
    //     components: ['index'],
    // },
]

// export const CTaskManagerNavigation = function (): TopNavigation[] {
//     const contents = ref([
//         {
//             name: 'Assign Task',
//             icon: 'material-symbols:task-outline',
//             href: route('dashboard.assign-task.index'),
//             components: ['dashboard/assign-task/index'],
//         },
//         {
//             name: 'Queuing Layout',
//             icon: 'ic:baseline-app-registration',
//             href: route('dashboard.queuing-layout.index'),
//             components: ['dashboard/queuing-layout/index'],
//         },
//         {
//             name: 'Ads Management',
//             icon: 'ic:baseline-ondemand-video',
//             href: route('dashboard.ads-management.index'),
//             components: ['dashboard/ads-management/index'],
//         },
//         {
//             name: 'Printers',
//             icon: 'ic:outline-local-printshop',
//             href: route('dashboard.printers.index'),
//             components: ['dashboard/printers/index'],
//         },
//     ])

//     return contents.value
// }

export const CSidebarNavigation = function (): TopNavigation[] {
    const $page = usePage()

    const contents = ref([
        {
            name: 'Dashboard',
            icon: 'ic:outline-space-dashboard',
            href: route('dashboard.index'),
            components: ['dashboard/index'],
        },
        {
            name: 'Reports',
            icon: 'material-symbols:report-outline',
            href: route('dashboard.reports.index'),
            components: ['dashboard/reports/index'],
        },
        {
            name: 'Employees',
            icon: 'ic:outline-people',
            href: route('dashboard.employees.index'),
            components: ['dashboard/employees/index'],
        },
        {
            name: 'Biometric Device Statuses',
            icon: 'ic:baseline-playlist-add-check',
            href: route('dashboard.biometric-device-statuses.index'),
            components: ['dashboard/biometric-device-statuses/index'],
        },
        {
            name: 'Employment Types',
            icon: 'ic:baseline-perm-contact-calendar',
            href: route('dashboard.employment-types.index'),
            components: [
                'dashboard/employment-types/index',
                'dashboard/employment-types/show',
                'dashboard/employment-types/edit',
            ],
        },
        {
            name: 'Check Statuses',
            icon: 'mdi:arrow-left-right-bold',
            href: route('dashboard.check-statuses.index'),
            components: [
                'dashboard/check-status/index',
                'dashboard/check-status/show',
                'dashboard/check-status/edit',
            ],
        },
        {
            name: 'Report Types',
            icon: 'mdi:alert-outline',
            href: route('dashboard.report-types.index'),
            components: [
                'dashboard/report-types/index',
                'dashboard/report-types/show',
                'dashboard/report-types/edit',
            ],
        },
        // {
        //     name: 'Employees',
        //     icon: 'ic:outline-people',
        //     href: route('dashboard.employees.index'),
        //     components: [
        //         'dashboard/employees/index',
        //         'dashboard/employees/edit',
        //         'dashboard/employees/create',
        //         'dashboard/employees/show',
        //     ],
        // },
        // {
        //     name: 'Offices',
        //     icon: 'mingcute:department-fill',
        //     href: route('dashboard.offices.index'),
        //     components: [
        //         'dashboard/offices/index',
        //         'dashboard/offices/edit',
        //         'dashboard/offices/create',
        //         'dashboard/offices/show',
        //         'dashboard/offices/showCheck',
        //     ],
        // },
        // {
        //     name: 'Colleges or Departments',
        //     icon: 'charm:graduate-cap',
        //     href: route('dashboard.colleges.index'),
        //     components: [
        //         'dashboard/colleges/index',
        //         'dashboard/colleges/edit',
        //         'dashboard/colleges/create',
        //         'dashboard/colleges/show',
        //         'dashboard/colleges/showCheck',
        //     ],
        // },
        // {
        //     name: 'Users',
        //     icon: 'fluent:people-add-16-regular',
        //     href: route('dashboard.users.index'),
        //     components: [
        //         'dashboard/users/index',
        //         'dashboard/users/edit',
        //         'dashboard/users/create',
        //     ],
        // },
        // {
        //     name: 'Notifications',
        //     icon: 'ic:outline-notifications',
        //     href: route('dashboard.notifications.index'),
        //     components: ['dashboard/notifications/index'],
        //     count: $page.props.unread_notifications_count,
        // },
        // {
        //     name: 'Profile',
        //     icon: 'material-symbols:person-outline',
        //     href: route('dashboard.profile.index'),
        //     components: ['dashboard/profile/index'],
        // },
        // {
        //     name: 'My Groups',
        //     icon: 'ic:outline-dashboard-customize',
        //     href: route('dashboard.my-groups.index'),
        //     components: ['dashboard/my-groups/index'],
        // },
        // {
        //     name: 'My Reports',
        //     icon: 'ic:outline-local-printshop',
        //     href: route('dashboard.my-reports.index'),
        //     components: ['dashboard/my-reports/index'],
        // },
        // {
        //     name: 'Profile',
        //     icon: 'ic:outline-person',
        //     href: route('dashboard.my-profile.index'),
        //     components: ['dashboard/my-profile/index'],
        // },
        // {
        //     name: 'Notifications',
        //     icon: 'ic:baseline-notifications-none',
        //     href: route('dashboard.notifications.index'),
        //     components: ['dashboard/notifications/index'],
        // },
        // {
        //     name: 'Account Settings',
        //     icon: 'ic:outline-settings',
        //     href: route('dashboard.account-settings.index'),
        //     components: ['dashboard/account-settings/index'],
        // },
    ])

    return contents.value
}
