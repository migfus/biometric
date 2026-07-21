declare module '@inertiajs/core' {
    interface PageProps {
        page_title: string
        flash?: Flash
        sidebar?: boolean
        auth?: Auth
        notifications?: AppNotification[]
        unread_notifications_count?: number
    }
}

export interface Auth {
    avatar: string
    created_at: string
    email: string
    id: string
    name: string
}

export interface Flash {
    error: {
        title: string
        content: string
    }
    success: {
        title: string
        content: string
    }
}

export interface AppNotification {
    id: string
    data: {
        title: string
        content: string
    }
    title: string
    content: string
    href: string
    read_at?: string | null
    created_at: string
}

export interface Switch {
    name: string
    icon: string
}

export interface CapturedPhoto {
    id: string
    preview: string
    preview_location: string
}

export interface BottomSheetData {
    name: string
    icon: string
    color: '' | 'danger'
    callback: () => void
}

export interface Check {
    id: number
    browser_id: string
    ip_address: string
    ip_location: string | null
    os: string
    employee_id: string
    check_in: boolean
    work_description: string
    rephrase_count: number
    created_at: string
    deleted_at?: string | null

    employee: Employee
    attachments: Attachment[]
    verified_user?: User | null
}

export interface Employee {
    id: string // employee_no
    full_name: string
    email: string | null
    created_at: string

    checks_count: number
    office: Office
    college?: College | null
    checks: Check[]
}

export interface Pagination<T> {
    current_page: number
    next_page_url: string | null
    data: T[]
    total: number
    last_page: number
}

export interface Attachment {
    id: number
    check_id: number
    file_location: string
    file_size: number
    preview_location: string
    created_at: string

    check: Check
}

export interface TopNavigation {
    name: string
    icon: string
    href: string
    active?: boolean
    components: string[]
    count?: number
}

export interface SearchFilter {
    display_name: string
    value: string
    icon: string
}

export interface User {
    id: string
    name: string
    avatar: string
    email: string
}

export interface College {
    id: number
    name: string
    created_at: string

    employees: Employee[]
    employees_count: number
}

export interface Office {
    id: number
    name: string
    created_at: string

    employees: Employee[]
    employees_count: number
}

export interface Paginate<T> {
    data: T[]
    current_page: number
    first_page_url: string
    from: number
    last_page: number
    last_page_url: string
    links: {
        url: string
        label: string
        page: string
        active: boolean
    }[]
    next_page_url: string
    path: string
    per_page: number
    prev_page_url: string
    to: number
    total: number
}
