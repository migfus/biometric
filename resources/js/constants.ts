import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import { TopNavigation } from './globalInterfaces'

const $page = usePage()

export const CTopNavigation: TopNavigation[] = [
    {
        name: 'Check In-Out',
        icon: 'ic:outline-home',
        href: route('index'),
        components: ['index'],
    },
]

export const GroupTasksNavigation = (group_id: string): TopNavigation[] => {
    const contents = ref([
        {
            name: 'Queuing',
            icon: 'mdi:ticket-outline',
            href: route('dashboard.group.tasks.queuing.index', group_id),
            components: ['dashboard/group/tasks/queuing/index'],
        },
        {
            name: `Processing`,
            icon: 'ic:baseline-access-time',
            href: route('dashboard.group.tasks.processing.index', group_id),
            components: ['dashboard/group/tasks/processing/index'],
        },
        {
            name: 'Completed',
            icon: 'ic:baseline-check-circle-outline',
            href: route('dashboard.group.tasks.completed.index', group_id),
            components: ['dashboard/group/tasks/completed/index'],
        },
        {
            name: 'Rejected',
            icon: 'ic:baseline-close',
            href: route('dashboard.group.tasks.rejected.index', group_id ?? ''),
            components: ['dashboard/group/tasks/rejected/index'],
        },
    ])

    return contents.value
}

export const GroupSettingsNavigation = (group_id?: string): TopNavigation[] => {
    const contents = ref([
        {
            name: 'Roles',
            icon: 'ic:outline-shield',
            href: route('dashboard.group.roles.index', group_id ?? ''),
            components: ['dashboard/group/roles/index'],
        },
        {
            name: `Edit Group`,
            icon: 'ic:baseline-display-settings',
            href: route('dashboard.group.edit.index', group_id ?? ''),
            components: ['dashboard/group/edit/index'],
        },
    ])

    return contents.value
}

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
    const contents = ref([
        {
            name: 'Dashboard',
            icon: 'ic:outline-space-dashboard',
            href: route('dashboard.index'),
            components: ['dashboard/index'],
        },
        {
            name: 'Check Status',
            icon: 'material-symbols:checklist',
            href: route('dashboard.check-status.index'),
            components: ['dashboard/check-status/index'],
        },
        {
            name: 'Checks',
            icon: 'mingcute:time-line',
            href: route('dashboard.checks.index'),
            components: [
                'dashboard/checks/index',
                'dashboard/checks/show',
                'dashboard/checks/edit',
            ],
        },
        {
            name: 'Employees',
            icon: 'ic:outline-people',
            href: route('dashboard.employees.index'),
            components: [
                'dashboard/employees/index',
                'dashboard/employees/edit',
                'dashboard/employees/create',
                'dashboard/employees/show',
            ],
        },
        {
            name: 'Offices',
            icon: 'mingcute:department-fill',
            href: route('dashboard.offices.index'),
            components: [
                'dashboard/offices/index',
                'dashboard/offices/edit',
                'dashboard/offices/create',
                'dashboard/offices/show',
                'dashboard/offices/showCheck',
            ],
        },
        {
            name: 'Colleges or Departments',
            icon: 'charm:graduate-cap',
            href: route('dashboard.colleges.index'),
            components: [
                'dashboard/colleges/index',
                'dashboard/colleges/edit',
                'dashboard/colleges/create',
                'dashboard/colleges/show',
                'dashboard/colleges/showCheck',
            ],
        },
        {
            name: 'Users',
            icon: 'fluent:people-add-16-regular',
            href: route('dashboard.users.index'),
            components: [
                'dashboard/users/index',
                'dashboard/users/edit',
                'dashboard/users/create',
            ],
        },
        {
            name: 'Notifications',
            icon: 'ic:outline-notifications',
            href: route('dashboard.notifications.index'),
            components: ['dashboard/notifications/index'],
        },
        {
            name: 'Profile',
            icon: 'material-symbols:person-outline',
            href: route('dashboard.profile.index'),
            components: ['dashboard/profile/index'],
        },
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
