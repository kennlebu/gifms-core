<?php

use Illuminate\Http\Request;

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */

/**
 * Grants Information Management System
 * @version 1.0.0
 */





Route::get('/', function (Request $request) {
    return ["version"=>"1.0.0"];
});



Route::post('/authenticate', [	'uses' => 'ApiAuthController@authenticate']);



















/**
 * POST add
 * Summary: Add a new lpo request to the store
 * Notes: lpo awaits approvals from relevant parties
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/lpo', 'LpoApi@add')->middleware('jwt.auth');
;
/**
 * PUT updateLpo
 * Summary: Update an existing LPO
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/lpo', 'LpoApi@updateLpo')->middleware('jwt.auth');
/**
 * DELETE deleteLpo
 * Summary: Deletes an lpo
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/lpo/{lpo_id}', 'LpoApi@deleteLpo')->middleware('jwt.auth');
/**
 * GET getLpoById
 * Summary: Find lpo by ID
 * Notes: Returns a single lpo
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/lpo/{lpo_id}', 'LpoApi@getLpoById')->middleware('jwt.auth');
/**
 * POST updateLpoWithForm
 * Summary: Updates a lpo with form data
 * Notes: updates each field when not set as null
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/lpo/{lpo_id}', 'LpoApi@updateLpoWithForm')->middleware('jwt.auth');
/**
 * GET lposGet
 * Summary: lpo List
 * Notes: The Lpos endpoint returns information about the LPO requested give the parameters injected. The response includes the Reference-No and other details about each lpo, and lists the products in the proper display order. 

 */
Route::GET('/lpos', 'LpoApi@lposGet')->middleware('jwt.auth');

























/**
 * POST addLpoStatus
 * Summary: Add a new lpo status to the store
 * Notes: lpo status
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/lpo_status', 'LPOStatusApi@addLpoStatus')->middleware('jwt.auth');
/**
 * PUT updateLpoStatus
 * Summary: Update an existing LPO Status
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/lpo_status', 'LPOStatusApi@updateLpoStatus')->middleware('jwt.auth');
/**
 * DELETE deleteLpoStatus
 * Summary: Deletes an lpo_status
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/lpo_status/{lpo_status_id}', 'LPOStatusApi@deleteLpoStatus')->middleware('jwt.auth');
/**
 * GET getLpoStatusById
 * Summary: Find lpo by ID
 * Notes: Returns a single lpo
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/lpo_status/{lpo_status_id}', 'LPOStatusApi@getLpoStatusById')->middleware('jwt.auth');
/**
 * GET lpoStatusesGet
 * Summary: lpo statuses List
 * Notes: The Lpo Statuses endpoint returns information about the LPO statuses requested give the parameters injected.  

 */
Route::GET('/lpo_statuses', 'LPOStatusApi@lpoStatusesGet')->middleware('jwt.auth');





























/**
 * POST addLpoQuotation
 * Summary: Add a new lpo quotation
 * Notes: new lpo quotaion
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/lpo_quotation', 'LPOQuotationApi@addLpoQuotation')->middleware('jwt.auth');
/**
 * PUT updateLpoQuotation
 * Summary: Update an existing LPO Quotation
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/lpo_quotation', 'LPOQuotationApi@updateLpoQuotation')->middleware('jwt.auth');
/**
 * DELETE deleteLpoQuotation
 * Summary: Deletes an lpo_quotation
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/lpo_quotation/{lpo_quotation_id}', 'LPOQuotationApi@deleteLpoQuotation')->middleware('jwt.auth');
/**
 * GET getLpoQuotationById
 * Summary: Find lpo quotation by ID
 * Notes: Returns a single lpo quotation
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/lpo_quotation/{lpo_quotation_id}', 'LPOQuotationApi@getLpoQuotationById')->middleware('jwt.auth');
/**
 * GET lpoQuotationsGet
 * Summary: lpo quotations List
 * Notes: The Lpo Quotations endpoint returns information about the LPO Quotation requested given the parameters injected.  

 */
Route::GET('/lpo_quotations', 'LPOQuotationApi@lpoQuotationsGet')->middleware('jwt.auth');






















/**
 * POST addLpoItem
 * Summary: Add a new lpo item
 * Notes: new lpo item
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/lpo_item', 'LPOItemApi@addLpoItem')->middleware('jwt.auth');
/**
 * PUT updateLpoItem
 * Summary: Update an existing LPO Item
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/lpo_item', 'LPOItemApi@updateLpoItem')->middleware('jwt.auth');
/**
 * DELETE deleteLpoItem
 * Summary: Deletes an lpo_item
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/lpo_item/{lpo_item_id}', 'LPOItemApi@deleteLpoItem')->middleware('jwt.auth');
/**
 * GET getLpoItemById
 * Summary: Find lpo item by ID
 * Notes: Returns a single lpo item
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/lpo_item/{lpo_item_id}', 'LPOItemApi@getLpoItemById')->middleware('jwt.auth');
/**
 * GET lpoItemsGet
 * Summary: lpo items List
 * Notes: The Lpo Items endpoint returns information about the LPO Item requested given the parameters injected.  

 */
Route::GET('/lpo_items', 'LPOItemApi@lpoItemsGet')->middleware('jwt.auth');






























/**
 * POST addLpoTerm
 * Summary: Add a new lpo term
 * Notes: new lpo term
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/lpo_term', 'LPOTermApi@addLpoTerm')->middleware('jwt.auth');
/**
 * PUT updateLpoTerm
 * Summary: Update an existing LPO Term
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/lpo_term', 'LPOTermApi@updateLpoTerm')->middleware('jwt.auth');
/**
 * DELETE deleteLpoTerm
 * Summary: Deletes an lpo_term
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/lpo_term/{lpo_term_id}', 'LPOTermApi@deleteLpoTerm')->middleware('jwt.auth');
/**
 * GET getLpoTermById
 * Summary: Find lpo term by ID
 * Notes: Returns a single lpo term
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/lpo_term/{lpo_term_id}', 'LPOTermApi@getLpoTermById')->middleware('jwt.auth');
/**
 * GET lpoTermsGet
 * Summary: lpo terms List
 * Notes: The Lpo Terms endpoint returns information about the LPO Term requested given the parameters injected.  

 */
Route::GET('/lpo_terms', 'LPOTermApi@lpoTermsGet')->middleware('jwt.auth');


































/**
 * POST addLpoComment
 * Summary: Add a new lpo comment
 * Notes: new lpo comment
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/lpo_comment', 'LPOCommentApi@addLpoComment')->middleware('jwt.auth');
/**
 * PUT updateLpoComment
 * Summary: Update an existing LPO Comment
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/lpo_comment', 'LPOCommentApi@updateLpoComment')->middleware('jwt.auth');
/**
 * DELETE deleteLpoComment
 * Summary: Deletes an lpo_comment
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/lpo_comment/{lpo_comment_id}', 'LPOCommentApi@deleteLpoComment')->middleware('jwt.auth');
/**
 * GET getLpoCommentById
 * Summary: Find lpo comment by ID
 * Notes: Returns a single lpo comment
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/lpo_comment/{lpo_comment_id}', 'LPOCommentApi@getLpoCommentById')->middleware('jwt.auth');
/**
 * GET lpoCommentsGet
 * Summary: lpo comments List
 * Notes: The Lpo Comments endpoint returns information about the LPO Comment requested given the parameters injected.  

 */
Route::GET('/lpo_comments', 'LPOCommentApi@lpoCommentsGet')->middleware('jwt.auth');
























/**
 * POST addLpoApproval
 * Summary: Add a new lpo approval
 * Notes: new lpo approval
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/lpo_approval', 'LPOApprovalApi@addLpoApproval')->middleware('jwt.auth');
/**
 * PUT updateLpoApproval
 * Summary: Update an existing LPO Approval
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/lpo_approval', 'LPOApprovalApi@updateLpoApproval')->middleware('jwt.auth');
/**
 * DELETE deleteLpoApproval
 * Summary: Deletes an lpo_approval
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/lpo_approval/{lpo_approval_id}', 'LPOApprovalApi@deleteLpoApproval')->middleware('jwt.auth');
/**
 * GET getLpoApprovalById
 * Summary: Find lpo approval by ID
 * Notes: Returns a single lpo approval
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/lpo_approval/{lpo_approval_id}', 'LPOApprovalApi@getLpoApprovalById')->middleware('jwt.auth');
/**
 * GET lpoApprovalsGet
 * Summary: lpo approvals List
 * Notes: The Lpo Approvals endpoint returns information about the LPO Approval requested given the parameters injected.  

 */
Route::GET('/lpo_approvals', 'LPOApprovalApi@lpoApprovalsGet')->middleware('jwt.auth');

























/**
 * POST addDepartment
 * Summary: Add a new department
 * Notes: new department
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/department', 'DepartmentApi@addDepartment')->middleware('jwt.auth');
/**
 * PUT updateDepartment
 * Summary: Update an existing department
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/department', 'DepartmentApi@updateDepartment')->middleware('jwt.auth');
/**
 * DELETE deleteDepartment
 * Summary: Deletes an department
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/department/{department_id}', 'DepartmentApi@deleteDepartment')->middleware('jwt.auth');
/**
 * GET getDepartmentById
 * Summary: Find department by ID
 * Notes: Returns a single department
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/department/{department_id}', 'DepartmentApi@getDepartmentById')->middleware('jwt.auth');
/**
 * GET departmentsGet
 * Summary: departments List
 * Notes: The department endpoint returns multiple department requested given the parameters injected.  

 */
Route::GET('/departments', 'DepartmentApi@departmentsGet')->middleware('jwt.auth');


























/**
 * POST addRight
 * Summary: Add a new right
 * Notes: new right
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/right', 'RightsApi@addRight')->middleware('jwt.auth');
/**
 * PUT updateRight
 * Summary: Update an existing right
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/right', 'RightsApi@updateRight')->middleware('jwt.auth');
/**
 * DELETE deleteRight
 * Summary: Deletes an right
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/right/{right_id}', 'RightsApi@deleteRight')->middleware('jwt.auth');
/**
 * GET getRightById
 * Summary: Find right by ID
 * Notes: Returns a single right
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/right/{right_id}', 'RightsApi@getRightById')->middleware('jwt.auth');
/**
 * GET rightsGet
 * Summary: rights List
 * Notes: The right endpoint returns multiple right requested given the parameters injected.  

 */
Route::GET('/rights', 'RightsApi@rightsGet')->middleware('jwt.auth');


































/**
 * POST addRole
 * Summary: Add a new role
 * Notes: new role
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/role', 'RolesApi@addRole')->middleware('jwt.auth');
/**
 * PUT updateRole
 * Summary: Update an existing role
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/role', 'RolesApi@updateRole')->middleware('jwt.auth');
/**
 * DELETE deleteRole
 * Summary: Deletes an role
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/role/{role_id}', 'RolesApi@deleteRole')->middleware('jwt.auth');
/**
 * GET getRoleById
 * Summary: Find role by ID
 * Notes: Returns a single role
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/role/{role_id}', 'RolesApi@getRoleById')->middleware('jwt.auth');
/**
 * GET rolesGet
 * Summary: roles List
 * Notes: The role endpoint returns multiple role requested given the parameters injected.  

 */
Route::GET('/roles', 'RolesApi@rolesGet')->middleware('jwt.auth');




































/**
 * POST addStaff
 * Summary: Add a new staff
 * Notes: new staff
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/staff', 'StaffApi@addStaff')->middleware('jwt.auth');
/**
 * PUT updateStaff
 * Summary: Update an existing staff
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/staff', 'StaffApi@updateStaff')->middleware('jwt.auth');
/**
 * DELETE deleteStaff
 * Summary: Deletes an staff
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/staff/{staff_id}', 'StaffApi@deleteStaff')->middleware('jwt.auth');
/**
 * GET getStaffById
 * Summary: Find staff by ID
 * Notes: Returns a single staff
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/staff/{staff_id}', 'StaffApi@getStaffById')->middleware('jwt.auth');
/**
 * GET staffsGet
 * Summary: staffs List
 * Notes: The staff endpoint returns multiple staff requested given the parameters injected.  

 */
Route::GET('/staffs', 'StaffApi@staffsGet')->middleware('jwt.auth');















/**
 * POST addProject
 * Summary: Add a new project
 * Notes: new project
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/project', 'ProjectApi@addProject')->middleware('jwt.auth');
/**
 * PUT updateProject
 * Summary: Update an existing project
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/project', 'ProjectApi@updateProject')->middleware('jwt.auth');
/**
 * DELETE deleteProject
 * Summary: Deletes an project
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/project/{project_id}', 'ProjectApi@deleteProject')->middleware('jwt.auth');
/**
 * GET getProjectById
 * Summary: Find project by ID
 * Notes: Returns a single project
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/project/{project_id}', 'ProjectApi@getProjectById')->middleware('jwt.auth');
/**
 * GET projectsGet
 * Summary: projects List
 * Notes: The project endpoint returns multiple project requested given the parameters injected.  

 */
Route::GET('/projects', 'ProjectApi@projectsGet')->middleware('jwt.auth');
/**
 * GET projectActivitiesGet
 * Summary: project_activities List
 * Notes: The project_activity endpoint returns multiple project_activity requested given the parameters injected.  

 */
Route::GET('/project_activities', 'ProjectActivityApi@projectActivitiesGet')->middleware('jwt.auth');





























/**
 * POST addProjectActivity
 * Summary: Add a new project_activity
 * Notes: new project_activity
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/project_activity', 'ProjectActivityApi@addProjectActivity')->middleware('jwt.auth');
/**
 * PUT updateProjectActivity
 * Summary: Update an existing project_activity
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/project_activity', 'ProjectActivityApi@updateProjectActivity')->middleware('jwt.auth');
/**
 * DELETE deleteProjectActivity
 * Summary: Deletes an project_activity
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/project_activity/{project_activity_id}', 'ProjectActivityApi@deleteProjectActivity')->middleware('jwt.auth');
/**
 * GET getProjectActivityById
 * Summary: Find project_activity by ID
 * Notes: Returns a single project_activity
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/project_activity/{project_activity_id}', 'ProjectActivityApi@getProjectActivityById')->middleware('jwt.auth');





























/**
 * POST addProjectBudgetAccount
 * Summary: Add a new project_budget_account
 * Notes: new project_budget_account
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/project_budget_account', 'ProjectBudgetAccountApi@addProjectBudgetAccount')->middleware('jwt.auth');
/**
 * PUT updateProjectBudgetAccount
 * Summary: Update an existing project_budget_account
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/project_budget_account', 'ProjectBudgetAccountApi@updateProjectBudgetAccount')->middleware('jwt.auth');
/**
 * DELETE deleteProjectBudgetAccount
 * Summary: Deletes an project_budget_account
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/project_budget_account/{project_budget_account_id}', 'ProjectBudgetAccountApi@deleteProjectBudgetAccount')->middleware('jwt.auth');
/**
 * GET getProjectBudgetAccountById
 * Summary: Find project_budget_account by ID
 * Notes: Returns a single project_budget_account
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/project_budget_account/{project_budget_account_id}', 'ProjectBudgetAccountApi@getProjectBudgetAccountById')->middleware('jwt.auth');
/**
 * GET projectBudgetAccountsGet
 * Summary: project_budget_accounts List
 * Notes: The project_budget_account endpoint returns multiple project_budget_account requested given the parameters injected.  

 */
Route::GET('/project_budget_accounts', 'ProjectBudgetAccountApi@projectBudgetAccountsGet')->middleware('jwt.auth');
































/**
 * POST addProjectCashNeeds
 * Summary: Add a new project_cash_needs
 * Notes: new project_cash_needs
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/project_cash_needs', 'ProjectCashNeedsApi@addProjectCashNeeds')->middleware('jwt.auth');
/**
 * PUT updateProjectCashNeeds
 * Summary: Update an existing project_cash_needs
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/project_cash_needs', 'ProjectCashNeedsApi@updateProjectCashNeeds')->middleware('jwt.auth');
/**
 * DELETE deleteProjectCashNeeds
 * Summary: Deletes an project_cash_needs
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/project_cash_needs/{project_cash_needs_id}', 'ProjectCashNeedsApi@deleteProjectCashNeeds')->middleware('jwt.auth');
/**
 * GET getProjectCashNeedsById
 * Summary: Find project_cash_needs by ID
 * Notes: Returns a single project_cash_needs
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/project_cash_needs/{project_cash_needs_id}', 'ProjectCashNeedsApi@getProjectCashNeedsById')->middleware('jwt.auth');
/**
 * GET projectCashNeedssGet
 * Summary: project_cash_needss List
 * Notes: The project_cash_needs endpoint returns multiple project_cash_needs requested given the parameters injected.  

 */
Route::GET('/project_cash_needss', 'ProjectCashNeedsApi@projectCashNeedssGet')->middleware('jwt.auth');































/**
 * POST addProjectMasterList
 * Summary: Add a new project_master_list
 * Notes: new project_master_list
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/project_master_list', 'ProjectMasterListApi@addProjectMasterList')->middleware('jwt.auth');
/**
 * PUT updateProjectMasterList
 * Summary: Update an existing project_master_list
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/project_master_list', 'ProjectMasterListApi@updateProjectMasterList')->middleware('jwt.auth');
/**
 * DELETE deleteProjectMasterList
 * Summary: Deletes an project_master_list
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/project_master_list/{project_master_list_id}', 'ProjectMasterListApi@deleteProjectMasterList')->middleware('jwt.auth');
/**
 * GET getProjectMasterListById
 * Summary: Find project_master_list by ID
 * Notes: Returns a single project_master_list
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/project_master_list/{project_master_list_id}', 'ProjectMasterListApi@getProjectMasterListById')->middleware('jwt.auth');
/**
 * GET projectMasterListsGet
 * Summary: project_master_lists List
 * Notes: The project_master_list endpoint returns multiple project_master_list requested given the parameters injected.  

 */
Route::GET('/project_master_lists', 'ProjectMasterListApi@projectMasterListsGet')->middleware('jwt.auth');

































/**
 * POST addProjectObjective
 * Summary: Add a new project_objective
 * Notes: new project_objective
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/project_objective', 'ProjectObjectiveApi@addProjectObjective')->middleware('jwt.auth');
/**
 * PUT updateProjectObjective
 * Summary: Update an existing project_objective
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/project_objective', 'ProjectObjectiveApi@updateProjectObjective')->middleware('jwt.auth');
/**
 * DELETE deleteProjectObjective
 * Summary: Deletes an project_objective
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/project_objective/{project_objective_id}', 'ProjectObjectiveApi@deleteProjectObjective')->middleware('jwt.auth');
/**
 * GET getProjectObjectiveById
 * Summary: Find project_objective by ID
 * Notes: Returns a single project_objective
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/project_objective/{project_objective_id}', 'ProjectObjectiveApi@getProjectObjectiveById')->middleware('jwt.auth');
/**
 * GET projectObjectivesGet
 * Summary: project_objectives List
 * Notes: The project_objective endpoint returns multiple project_objective requested given the parameters injected.  

 */
Route::GET('/project_objectives', 'ProjectObjectiveApi@projectObjectivesGet')->middleware('jwt.auth');






























/**
 * POST addProjectTeam
 * Summary: Add a new project_team
 * Notes: new project_team
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/project_team', 'ProjectTeamApi@addProjectTeam')->middleware('jwt.auth');
/**
 * PUT updateProjectTeam
 * Summary: Update an existing project_team
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/project_team', 'ProjectTeamApi@updateProjectTeam')->middleware('jwt.auth');
/**
 * DELETE deleteProjectTeam
 * Summary: Deletes an project_team
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/project_team/{project_team_id}', 'ProjectTeamApi@deleteProjectTeam')->middleware('jwt.auth');
/**
 * GET getProjectTeamById
 * Summary: Find project_team by ID
 * Notes: Returns a single project_team
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/project_team/{project_team_id}', 'ProjectTeamApi@getProjectTeamById')->middleware('jwt.auth');
/**
 * GET projectTeamsGet
 * Summary: project_teams List
 * Notes: The project_team endpoint returns multiple project_team requested given the parameters injected.  

 */
Route::GET('/project_teams', 'ProjectTeamApi@projectTeamsGet')->middleware('jwt.auth');


















/**
 * POST addSupplier
 * Summary: Add a new supplier
 * Notes: new supplier
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/supplier', 'SupplierApi@addSupplier')->middleware('jwt.auth');
/**
 * PUT updateSupplier
 * Summary: Update an existing supplier
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/supplier', 'SupplierApi@updateSupplier')->middleware('jwt.auth');
/**
 * DELETE deleteSupplier
 * Summary: Deletes an supplier
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/supplier/{supplier_id}', 'SupplierApi@deleteSupplier')->middleware('jwt.auth');
/**
 * GET getSupplierById
 * Summary: Find supplier by ID
 * Notes: Returns a single supplier
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/supplier/{supplier_id}', 'SupplierApi@getSupplierById')->middleware('jwt.auth');
/**
 * GET suppliersGet
 * Summary: suppliers List
 * Notes: The supplier endpoint returns multiple supplier requested given the parameters injected.  

 */
Route::GET('/suppliers', 'SupplierApi@suppliersGet')->middleware('jwt.auth');

































/**
 * POST addSupplierRate
 * Summary: Add a new supplier_rate
 * Notes: new supplier_rate
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/supplier_rate', 'SupplierRateApi@addSupplierRate')->middleware('jwt.auth');
/**
 * PUT updateSupplierRate
 * Summary: Update an existing supplier_rate
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/supplier_rate', 'SupplierRateApi@updateSupplierRate')->middleware('jwt.auth');
/**
 * DELETE deleteSupplierRate
 * Summary: Deletes an supplier_rate
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/supplier_rate/{supplier_rate_id}', 'SupplierRateApi@deleteSupplierRate')->middleware('jwt.auth');
/**
 * GET getSupplierRateById
 * Summary: Find supplier_rate by ID
 * Notes: Returns a single supplier_rate
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/supplier_rate/{supplier_rate_id}', 'SupplierRateApi@getSupplierRateById')->middleware('jwt.auth');
/**
 * GET supplierRatesGet
 * Summary: supplier_rates List
 * Notes: The supplier_rate endpoint returns multiple supplier_rate requested given the parameters injected.  

 */
Route::GET('/supplier_rates', 'SupplierRateApi@supplierRatesGet')->middleware('jwt.auth');






































/**
 * GET supplyCategoriesGet
 * Summary: supply_categories List
 * Notes: The supply_category endpoint returns multiple supply_category requested given the parameters injected.  

 */
Route::GET('/supply_categories', 'SupplyCategoryApi@supplyCategoriesGet')->middleware('jwt.auth');
/**
 * POST addSupplyCategory
 * Summary: Add a new supply_category
 * Notes: new supply_category
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/supply_category', 'SupplyCategoryApi@addSupplyCategory')->middleware('jwt.auth');
/**
 * PUT updateSupplyCategory
 * Summary: Update an existing supply_category
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/supply_category', 'SupplyCategoryApi@updateSupplyCategory')->middleware('jwt.auth');
/**
 * DELETE deleteSupplyCategory
 * Summary: Deletes an supply_category
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/supply_category/{supply_category_id}', 'SupplyCategoryApi@deleteSupplyCategory')->middleware('jwt.auth');
/**
 * GET getSupplyCategoryById
 * Summary: Find supply_category by ID
 * Notes: Returns a single supply_category
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/supply_category/{supply_category_id}', 'SupplyCategoryApi@getSupplyCategoryById')->middleware('jwt.auth');

































/**
 * POST addSupplyCategoryItem
 * Summary: Add a new supply_category_item
 * Notes: new supply_category_item
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/supply_category_item', 'SupplyCategoryItemApi@addSupplyCategoryItem')->middleware('jwt.auth');
/**
 * PUT updateSupplyCategoryItem
 * Summary: Update an existing supply_category_item
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/supply_category_item', 'SupplyCategoryItemApi@updateSupplyCategoryItem')->middleware('jwt.auth');
/**
 * DELETE deleteSupplyCategoryItem
 * Summary: Deletes an supply_category_item
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/supply_category_item/{supply_category_item_id}', 'SupplyCategoryItemApi@deleteSupplyCategoryItem')->middleware('jwt.auth');
/**
 * GET getSupplyCategoryItemById
 * Summary: Find supply_category_item by ID
 * Notes: Returns a single supply_category_item
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/supply_category_item/{supply_category_item_id}', 'SupplyCategoryItemApi@getSupplyCategoryItemById')->middleware('jwt.auth');
/**
 * GET supplyCategoryItemsGet
 * Summary: supply_category_items List
 * Notes: The supply_category_item endpoint returns multiple supply_category_item requested given the parameters injected.  

 */
Route::GET('/supply_category_items', 'SupplyCategoryItemApi@supplyCategoryItemsGet')->middleware('jwt.auth');



























/**
 * GET currenciesGet
 * Summary: currencies List
 * Notes: The currency endpoint returns multiple currency requested given the parameters injected.  

 */
Route::GET('/currencies', 'CurrencyApi@currenciesGet')->middleware('jwt.auth');
/**
 * POST addCurrency
 * Summary: Add a new currency
 * Notes: new currency
 * Output-Formats: [application/json, application/xml]
 */
Route::POST('/currency', 'CurrencyApi@addCurrency')->middleware('jwt.auth');
/**
 * PUT updateCurrency
 * Summary: Update an existing currency
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::PUT('/currency', 'CurrencyApi@updateCurrency')->middleware('jwt.auth');
/**
 * DELETE deleteCurrency
 * Summary: Deletes an currency
 * Notes: 
 * Output-Formats: [application/json, application/xml]
 */
Route::DELETE('/currency/{currency_id}', 'CurrencyApi@deleteCurrency')->middleware('jwt.auth');
/**
 * GET getCurrencyById
 * Summary: Find currency by ID
 * Notes: Returns a single currency
 * Output-Formats: [application/json, application/xml]
 */
Route::GET('/currency/{currency_id}', 'CurrencyApi@getCurrencyById')->middleware('jwt.auth');

