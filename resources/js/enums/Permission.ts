enum Permission {
    CREATE_ROLES = 'create-roles',
    VIEW_ROLES = 'view-roles',
    UPDATE_ROLES = 'update-roles',
    DELETE_ROLES = 'delete-roles',

    CREATE_STATUSES = 'create-statuss',
    VIEW_STATUSES = 'view-statuss',
    UPDATE_STATUSES = 'update-statuss',
    DELETE_STATUSES = 'delete-statuss',

    CREATE_PERMISSIONS = 'create-permissions',
    VIEW_PERMISSIONS = 'view-permissions',
    UPDATE_PERMISSIONS = 'update-permissions',
    DELETE_PERMISSIONS = 'delete-permissions',

    CREATE_USERS = 'create-users',
    VIEW_USERS = 'view-users',
    UPDATE_USERS = 'update-users',
    DELETE_USERS = 'delete-users',

    ASSIGN_ROLES = 'assign-role',
    ASSIGN_PERMISSIONS = 'assign-permission',

    VIEW_DIGITAL_COLLECTION = 'view-digital-collection',
}

export default Permission;
