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
    callback: () => void
}
