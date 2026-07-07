declare module '@inertiajs/core' {
    interface PageProps {
        page_title: string
        flash?: Flash
    }
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

export interface Switch {
    name: string
    icon: string
}

export interface CapturedPhoto {
    id: string
    preview: string
    file: File
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
    ip_location: string
    os: string
    employee_id: string
    check_in: boolean
    work_description: string
    rephrase_count: number
    created_at: string

    employee: Employee
    attachments: Attachment[]
}

export interface Employee {
    id: string // employee_no
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
