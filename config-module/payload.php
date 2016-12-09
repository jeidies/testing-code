<?php return array (
  'admins' => 
  array (
    'create' => 'a:8:{i:0;a:1:{s:6:"master";s:2:"id";}i:1;a:2:{s:6:"master";s:11:"uShortLabel";s:4:"name";s:8:"username";}i:2;a:2:{s:6:"master";s:5:"label";s:4:"name";s:8:"password";}i:3;a:2:{s:6:"master";s:6:"uLabel";s:4:"name";s:5:"email";}i:4;a:2:{s:6:"master";s:9:"falseBool";s:4:"name";s:9:"is_system";}i:5;a:2:{s:6:"master";s:4:"bool";s:4:"name";s:9:"is_active";}s:10:"timestamps";b:1;s:3:"log";a:1:{s:6:"object";s:8:"username";}}',
  ),
  'admin_roles' => 
  array (
    'create' => 'a:4:{i:0;a:1:{s:6:"master";s:2:"id";}i:1;a:3:{s:6:"master";s:3:"int";s:4:"name";s:8:"admin_id";s:9:"reference";s:9:"admins.id";}i:2;a:2:{s:6:"master";s:3:"int";s:4:"name";s:7:"role_id";}s:3:"log";a:2:{s:6:"create";a:3:{i:0;s:8:"assigned";i:1;s:15:"admins.username";i:2;s:11:"roles.title";}s:6:"update";a:3:{i:0;s:8:"unsigned";i:1;s:15:"admins.username";i:2;s:11:"roles.title";}}}',
  ),
  'roles' => 
  array (
    'create' => 'a:7:{i:0;a:1:{s:6:"master";s:2:"id";}i:1;a:2:{s:6:"master";s:11:"uShortLabel";s:4:"name";s:5:"title";}i:2;a:2:{s:6:"master";s:4:"bool";s:4:"name";s:8:"is_admin";}i:3;a:2:{s:6:"master";s:9:"falseBool";s:4:"name";s:9:"is_system";}i:4;a:2:{s:6:"master";s:4:"bool";s:4:"name";s:9:"is_active";}s:10:"timestamps";b:1;s:3:"log";a:2:{s:5:"group";s:4:"role";s:6:"object";s:5:"title";}}',
  ),
);