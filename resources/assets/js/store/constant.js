/**
 * @author  KHUN Ratana
 * @email ratana.k@sahakrinpheap.com.kh
 */


/**
 * ========================================
 *  - BE CAREFUL: When you change value
 *  in const, you must change constant
 *  value in PHP constant.
 * ========================================
 */


/**
 *
 * @type {({title: string, value: number}|{title: string, value: number}|{title: string, value: number}|{title: string, value: number})[]}
 */
export const CONTRACT_ACTIVE_TYPE = [
    // {'title':'PROBATION', 'value': 1},
    {'title':'FDC', 'value': 2},
    {'title':'DEMOTE', 'value': 6},
    {'title':'PROMOTE', 'value': 7},
    {'title':'UDC', 'value': 10},
    {'title':'CHANGE_LOCATION', 'value': 9}
];

/**
 *
 * @type {{RESIGN: number, TERMINATE: number, LAY_OFF: number, DEATH: number}}
 */
export const CONTRACT_END_TYPE = [
    {'title':'RESIGN', 'value': 3},
    {'title':'DEATH', 'value': 4},
    {'title':'TERMINATE', 'value': 5},
    {'title':'LAY_OFF', 'value': 8}
];

/**
 *
 * @type {{ACTIVE: number, END_CONTRACT: number, MOVEMENT: number}}
 */
export const REPORT_EXPORT_TYPE = [
    {'title':'ACTIVE', 'value': 0},
    {'title':'END_CONTRACT', 'value': 1},
    {'title':'MOVEMENT', 'value': 2}
];

export const PER_PAGE = 20;

export const QUESTION_TYPE = [
    {'title':'Open Question', 'value': 0},
    {'title':'Multiple Choice', 'value': 1},
    {'title':'Close Question', 'value': 2}
];

export const ARE_YOU_AUDIT_ALREADY = "តើអ្នកបានត្រួតពិនិត្យហើយ ឬ នៅ?";
export const SUCCESSFUL = "ជោគជ័យ";
export const FAILED = "បរាជ័យ";


