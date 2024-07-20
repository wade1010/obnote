CPP 核心编程9-STL案例-员工分组

![](https://gitee.com/hxc8/images3/raw/master/img/202407172237364.jpg)

 

```
#include "iostream"
#include "vector"
#include "map"

#define PLAN_DEPT 1
#define ART_DEPT 2
#define DEVELOP_DEPT 3

using namespace std;

class Worker {
public:
    string m_Name;
    int m_Salary;
};

void printWorker(const vector<Worker> &v) {
    for (vector<Worker>::const_iterator it = v.begin(); it != v.end(); it++) {
        cout << "姓名：" << it->m_Name << " 工资" << it->m_Salary << "元" << endl;
    }
}

void createWorker(vector<Worker> &v) {
    string nameSeed = "ABCDEFGHIJ";
    for (int i = 0; i < 10; i++) {
        Worker w;
//        w.m_Name = "员工" + nameSeed[i];//这里不能直接拼接字面量，否则打印的姓名结果如下
/*姓名：_M_realloc_insert 工资9589元
姓名：M_realloc_insert 工资8360元
姓名：_realloc_insert 工资11986元
姓名：realloc_insert 工资14512元
姓名：ealloc_insert 工资9893元
姓名：alloc_insert 工资14757元
姓名：lloc_insert 工资15821元
姓名：loc_insert 工资15889元
姓名：oc_insert 工资11668元
姓名：c_insert 工资14681元
*/
        w.m_Name = string("员工") + nameSeed[i];
        w.m_Salary = rand() % 8000 + 8000;
        v.push_back(w);
    }
}

void setRandGroup(vector<Worker> &v, multimap<int, Worker> &m) {
    for (vector<Worker>::iterator it = v.begin(); it != v.end(); it++) {
        int deptNum = rand() % 3 + 1;
        m.insert(make_pair(deptNum, *it));
    }
}

void doPrintGroup(string deptName, int deptId, const multimap<int, Worker> &m) {
    cout << deptName << "：" << endl;
    multimap<int, Worker>::const_iterator it = m.find(deptId);
    int num = m.count(deptId);
    while (num-- > 0) {
        cout << "人员:" << it->second.m_Name << "，薪资：" << it->second.m_Salary << "元" << endl;
        it++;
    }
}

void printGroup(const multimap<int, Worker> &m) {
    doPrintGroup("策划部门", PLAN_DEPT, m);
    doPrintGroup("美术部门", ART_DEPT, m);
    doPrintGroup("研发部门", DEVELOP_DEPT, m);
}

void test() {
    //创建员工
    vector<Worker> v;
    createWorker(v);
    printWorker(v);
    //员工分组
    multimap<int, Worker> mmap;
    setRandGroup(v, mmap);
    printGroup(mmap);
}

int main() {
    srand((unsigned int) time(NULL));
    test();
    return 0;
}
姓名：员工A 工资10578元
姓名：员工B 工资10025元
姓名：员工C 工资14594元
姓名：员工D 工资9416元
姓名：员工E 工资13054元
姓名：员工F 工资15969元
姓名：员工G 工资8737元
姓名：员工H 工资12460元
姓名：员工I 工资14454元
姓名：员工J 工资12137元
策划部门：
人员:员工A，薪资：10578元
人员:员工H，薪资：12460元
美术部门：
人员:员工C，薪资：14594元
人员:员工E，薪资：13054元
研发部门：
人员:员工B，薪资：10025元
人员:员工D，薪资：9416元
人员:员工F，薪资：15969元
人员:员工G，薪资：8737元
人员:员工I，薪资：14454元
人员:员工J，薪资：12137元
```