## 基于lumen创建的原始架构设计
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

### 目录结构
 - 根目录
    + app
        - Common
            - BaseClasses   抽象基类目录
            - Constants     常量目录
            - Interfaces    基础接口目录
            - Traits        trait目录
            - common.php    全局公共函数库
            - Helper.php    全局公用辅助类
        - Console
        - Events
        - Exceptions
        - Http
        - Jobs
        - Listeners
        - Models
        - Services
    + config
    + readme
    + routes
    + tests