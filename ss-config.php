<?php
/** 
 * SimSite 基础配置文件。
 *
 * 本文件包含以下配置选项：MySQL 设置、数据库表名前缀
 *
 * 您需要手动输入相关信息。
 */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //

/** MySQL 数据库名称 */
define('DB_NAME', 'qxccj');

/** MySQL 数据库用户名 */
define('DB_USER', 'root');

/** MySQL 数据库密码 */
define('DB_PASSWORD', '753951');

/** MySQL 主机 */
define('DB_HOST', 'localhost');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8');

/** 数据库整理类型。如不确定请勿更改 */
define('DB_COLLATE', '');

/** 数据表前缀。如需在同一个数据库中安装多个站点，请使用不同的表前缀 */
define('DB_TBL_PREFIX', 'ss_');
?>
