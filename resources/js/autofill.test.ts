import { describe, expect, it } from 'vitest'
import { applyAutofillFields, CheckFormFields } from './autofill'

describe('applyAutofillFields', () => {
    it('fills only employee_no, full_name, college, and office', () => {
        const current_form: CheckFormFields = {
            employee_no: 'OLD-0001',
            full_name: 'OLD NAME',
            college: 'OLD COLLEGE',
            office: 'OLD OFFICE',
            check: 'Check Out',
            work_description: 'Current work details',
        }

        const autofill_form: CheckFormFields = {
            employee_no: 'NEW-1234',
            full_name: 'NEW NAME',
            college: 'NEW COLLEGE',
            office: 'NEW OFFICE',
            check: 'Check In',
            work_description: 'Autofill description should not override',
        }

        const result = applyAutofillFields(current_form, autofill_form)

        expect(result.employee_no).toBe('NEW-1234')
        expect(result.full_name).toBe('NEW NAME')
        expect(result.college).toBe('NEW COLLEGE')
        expect(result.office).toBe('NEW OFFICE')

        expect(result.check).toBe('Check Out')
        expect(result.work_description).toBe('Current work details')
    })

    it('does not mutate the original current form object', () => {
        const current_form: CheckFormFields = {
            employee_no: 'EMP-01',
            full_name: 'Current User',
            college: 'Current College',
            office: 'Current Office',
            check: 'Check In',
            work_description: 'Do not change me',
        }

        const result = applyAutofillFields(current_form, {
            employee_no: 'EMP-02',
            full_name: 'Autofill User',
            college: 'Autofill College',
            office: 'Autofill Office',
            check: 'Check Out',
            work_description: 'Ignore this',
        })

        expect(result).not.toBe(current_form)
        expect(current_form.employee_no).toBe('EMP-01')
        expect(current_form.work_description).toBe('Do not change me')
    })
})
