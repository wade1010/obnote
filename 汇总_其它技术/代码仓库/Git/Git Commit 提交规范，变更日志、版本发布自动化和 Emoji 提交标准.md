### type

> 用于说明 commit 的类别，只允许使用下面9个标识。


| Type(中文) | Type类型(英文) | 描述 | 
| -- | -- | -- |
| 功能 | feat | 新增 feature | 
| 修复 | fix | 修复 bug | 
| 文档 | docs | 仅仅修改了文档，比如 README, CHANGELOG, CONTRIBUTE等等 | 
| 格式 | style | 仅仅修改了空格、格式缩进、逗号等等，不改变代码逻辑 | 
| 重构 | refactor | 代码重构，没有加新功能或者修复 bug | 
| 优化 | perf | 优化相关，比如提升性能、体验 | 
| 测试 | test | 测试用例，包括单元测试、集成测试等 | 
| 更新 | chore | 改变构建流程、或者增加依赖库、工具等 | 
| 回滚 | revert | 回滚到上一个版本 | 


### scope（可选项）

用于说明 commit 影响的范围，比如Enddevice、G2、G4、R1、Ford等等，视项目不同而不同。

### subject

是 commit 目的的简短描述，不超过50个字符。比如是修复一个 bug 或是增加一个 feature，类型如下：

- 以动词开头，使用第一人称现在时，比如change，而不是changed或changes

- 第一个字母小写

- 结尾不加句号（.）

### 示例(英文)

![](https://gitee.com/hxc8/images5/raw/master/img/202407180000260.jpg)

### 示例(中文)

![](https://gitee.com/hxc8/images5/raw/master/img/202407180000244.jpg)

## Git分支与版本发布规范

- 基本原则：master为保护分支，不直接在master上进行代码修改和提交。

- 开发日常需求或者项目时，从master分支上checkout一个feature分支进行开发或者bugfix分支进行bug修复，功能测试完毕并且项目发布上线后，将feature分支合并到主干master，并且打Tag发布，最后删除开发分支。分支命名规范：

	- 分支版本命名规则：分支类型 _ 分支发布时间 _ 分支功能。比如：feat_20170401_fairy_flower

	- 分支类型包括：feat、 fix、refactor三种类型，即新功能开发、bug修复和代码重构

	- 时间使用年月日进行命名，不足2位补0

	- 分支功能命名使用snake case命名法，即下划线命名。

- Tag包括3位版本，前缀使用v。比如v1.2.31。Tag命名规范：

	- 新功能开发使用第2位版本号，bug修复使用第3位版本号

	- 核心基础库或者Node中间价可以在大版本发布请使用灰度版本号，在版本后面加上后缀，用中划线分隔。alpha或者belta后面加上次数，即第几次alpha：

		- v2.0.0-alpha.1

		- v2.0.0-belta.1

- 版本正式发布前需要生成changelog文档，然后再发布上线。

message 样例：

```text
<type>(<scope>): <subject>
<BLANK LINE>
<body>
<BLANK LINE>
<footer>
```

- Type：必须是下列之一

	- **feat**：一个新功能

	- **fix**：bug 修复

	- **docs**：编辑文档

	- **style**：不影响代码含义的更改 (空格、格式、缺少分号等，不是 css 的更改)

	- **refactor**：既不修复 bug 也不添加特性的代码更改

	- **perf**：提高性能的代码更改

	- **test**：添加缺失的或纠正现有的测试

	- **chore**：对构建过程或辅助工具和库 (如文档生成)的更改

- **Subject**：主题包含对变更的简洁描述

- **Body**：具体的修改内容，可以包括与之前的对比

- **Footer**：通常是 BREAKING CHANGE 或修复的 issue 链接