export interface CheckFormFields {
    employee_no: string
    full_name: string
    college: string
    office: string
    check: string
    work_description: string
}

export function applyAutofillFields<T extends CheckFormFields>(
    current_form: T,
    autofill_form: CheckFormFields,
): T {
    return {
        ...current_form,
        employee_no: autofill_form.employee_no,
        full_name: autofill_form.full_name,
        college: autofill_form.college,
        office: autofill_form.office,
    }
}
