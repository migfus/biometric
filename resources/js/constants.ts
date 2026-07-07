import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import { GroupCommunity, TopNavigation } from './globalTypes'

const $page = usePage()

export const CTopNavigation: TopNavigation[] = [
    {
        name: 'Home',
        icon: 'ic:outline-home',
        href: route('index'),
        components: ['home/index'],
    },
]

export const GroupCommunityNavigation = (
    group_communities: GroupCommunity[],
    group_id: string,
): TopNavigation[] => {
    const contents = ref([
        // {
        //     name: 'Announcements',
        //     icon: 'ic:outline-push-pin',
        //     href: route('dashboard.assign-task.index'),
        //     components: ['dashboard/assign-task/index'],
        // },
        // {
        //     name: 'General',
        //     icon: 'ic:baseline-chat-bubble-outline',
        //     href: route('dashboard.queuing-layout.index'),
        //     components: ['dashboard/queuing-layout/index'],
        // },
        // {
        //     name: 'Rules',
        //     icon: 'pajamas:push-rules',
        //     href: route('dashboard.queuing-layout.index'),
        //     components: ['dashboard/queuing-layout/index'],
        // },
        ...group_communities.map((data: GroupCommunity) => {
            return {
                name: data.name,
                icon: data.icon,
                href: route('dashboard.group.c.show', {
                    group: group_id,
                    c: data.id,
                }),
                components: ['dashboard/group/communities/show'],
            }
        }),
    ])

    return contents.value
}

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

export const CTaskManagerNavigation = function (): TopNavigation[] {
    const contents = ref([
        {
            name: 'Assign Task',
            icon: 'material-symbols:task-outline',
            href: route('dashboard.assign-task.index'),
            components: ['dashboard/assign-task/index'],
        },
        {
            name: 'Queuing Layout',
            icon: 'ic:baseline-app-registration',
            href: route('dashboard.queuing-layout.index'),
            components: ['dashboard/queuing-layout/index'],
        },
        {
            name: 'Ads Management',
            icon: 'ic:baseline-ondemand-video',
            href: route('dashboard.ads-management.index'),
            components: ['dashboard/ads-management/index'],
        },
        {
            name: 'Printers',
            icon: 'ic:outline-local-printshop',
            href: route('dashboard.printers.index'),
            components: ['dashboard/printers/index'],
        },
    ])

    return contents.value
}

export const CSidebarNavigation = function (): TopNavigation[] {
    const contents = ref([
        {
            name: 'Dashboard',
            icon: 'ic:outline-space-dashboard',
            href: route('dashboard.index'),
            components: ['dashboard/index/index'],
        },
        {
            name: 'My Tasks',
            icon: 'material-symbols:task-outline',
            href: route('dashboard.my-tasks.index'),
            components: ['dashboard/my-tasks/index', 'dashboard/my-tasks/show'],
        },
        {
            name: 'My Groups',
            icon: 'ic:outline-dashboard-customize',
            href: route('dashboard.my-groups.index'),
            components: ['dashboard/my-groups/index'],
        },
        // {
        //     name: 'My Reports',
        //     icon: 'ic:outline-local-printshop',
        //     href: route('dashboard.my-reports.index'),
        //     components: ['dashboard/my-reports/index'],
        // },
        {
            name: 'Profile',
            icon: 'ic:outline-person',
            href: route('dashboard.my-profile.index'),
            components: ['dashboard/my-profile/index'],
        },
        {
            name: 'Notifications',
            icon: 'ic:baseline-notifications-none',
            href: route('dashboard.notifications.index'),
            components: ['dashboard/notifications/index'],
        },
        {
            name: 'Account Settings',
            icon: 'ic:outline-settings',
            href: route('dashboard.account-settings.index'),
            components: ['dashboard/account-settings/index'],
        },
    ])

    return contents.value
}

export const CAdminNavigation = function (): TopNavigation[] {
    const contents = ref<
        {
            name: string
            icon: string
            href: string
            components: string[]
        }[]
    >([])

    if ($page.props.system_permissions != null) {
        if ($page.props.system_permissions.includes('group-index')) {
            contents.value.push({
                name: 'Manage Groups',
                icon: 'ic:outline-dashboard-customize',
                href: route('dashboard.manage-groups.index'),
                components: [
                    'dashboard/manage-groups/index/index',
                    'dashboard/manage-groups/edit/edit',
                ],
            })
        }
        if ($page.props.system_permissions.includes('user-index')) {
            contents.value.push({
                name: 'Manage Users',
                icon: 'ic:outline-people',
                href: route('dashboard.manage-users.index'),
                components: [
                    'dashboard/manage-users/index/index',
                    'dashboard/manage-users/edit/edit',
                ],
            })
        }
        if (
            $page.props.system_permissions.includes(
                'system-roles-permissions-index',
            )
        ) {
            contents.value.push({
                name: "System's Roles & Permission",
                icon: 'ic:outline-shield',
                href: route('dashboard.system-roles-permissions.index'),
                components: ['dashboard/system-roles-permissions/index'],
            })
        }
        if ($page.props.system_permissions.includes('system-index')) {
            contents.value.push({
                name: 'System Settings',
                icon: 'ic:outline-display-settings',
                href: route('dashboard.system-settings.index'),
                components: ['dashboard/system-settings/index'],
            })
        }
    }

    return contents.value
}
