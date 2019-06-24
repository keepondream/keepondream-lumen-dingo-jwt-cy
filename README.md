## 基于lumen创建的初始设计
- version: 5.8.* 
- author: WangSx
### 设计初衷,要点:
    
    1.验证层(request)
    2.服务层(service)
    3.DAO层(database access object)
    4.脚本自动构建模板代码
    5.拥有MongoDB
    6.缓存层
    7.单元测试.流程测试

## 目录结构析
Author: WangSx
 - app
 	+ Common
		- Constants # 常量目录
		- common.php # 全局公共函数文件
		- Helper.php # 全局公共辅助方法类文件
	+ Console # 脚本目录
	+ Events # 异步/同步事件目录
	+ Http
		- Controllers
			+ Backend # **后台管理控制器目录**
			+ Frontend # **应用api控制器目录**
	+ Jobs # 异步/同步任务目录
	+ Listeners # 监听目录
	+ Models # 模型目录
	+ Observers # 观察者目录
	+ Services ## **业务层服务目录**
	```
		services目录说明:
		1.每个控制器对应一个业务服务层,且需要静态实例该服务
		2.每个业务层只做自己的事情,🚫🈲止耦合,需要交叉可用xxxConstroller::getService()对应的服务进行处理
	```
 - routes
 	+ api.php # 应用api路由文件
	+ backend.php # 后台管理路由文件
 - supervisor
 	+ online # 生产所需队列监听配置文件
 - tests
 	+ Featrue
		- Backend # 后台功能测试目录
			+ V1 # 版本标识
				AdminUser # 管理员模块
		- Frontend # 应用api功能测试目录
			+ V1 # 版本标识
				User # 用户模块
	+ FrontendTestCase.php # **应用api测试case基类,已实现用户认证**
	+ BackendTestCase.php # **后台管理测试case基类,已实现用户认证**
	
	
	定版
