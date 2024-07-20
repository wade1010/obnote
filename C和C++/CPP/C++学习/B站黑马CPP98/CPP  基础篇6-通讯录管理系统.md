CPP  基础篇6-通讯录管理系统

```
#include "iostream"

using namespace std;

#define MAX 1000

//设计联系人结构体
struct Person {
    string m_Name;
    int m_Sex;//性别 1 男 2 女
    int m_Age;
    string m_Phone;
    string m_Addr;
};
struct AddressBooks {
    struct Person personArray[MAX];
    int m_Size;//联系人数量
};

//设计通讯录结构体
void showMenu() {
    cout << "1、添加联系人" << endl;
    cout << "2、显示联系人" << endl;
    cout << "3、删除联系人" << endl;
    cout << "4、查找联系人" << endl;
    cout << "5、修改联系人" << endl;
    cout << "6、清空联系人" << endl;
    cout << "0、退出通讯录" << endl;
}

void collectInfo(AddressBooks *abs, int size) {
    cout << "请输入姓名" << endl;
    string name;
    cin >> name;
    abs->personArray[size].m_Name = name;


    cout << "请输入性别" << endl;
    cout << "1----男" << endl;
    cout << "2----女" << endl;
    int sex;
    while (true) {
        cin >> sex;
        if (sex == 1 || sex == 2) {
            abs->personArray[size].m_Sex = sex;
            break;
        }
        cout << "输入有误，请重新输入" << endl;
    }

    cout << "请输入年龄" << endl;
    int age = 0;
    while (true) {
        cin >> age;
        if (age > 0 && age < 120) {
            abs->personArray[size].m_Age = age;
            break;
        }
        cout << "输入有误，请重新输入" << endl;
    }

    cout << "请输入电话" << endl;
    string phone;
    cin >> phone;
    abs->personArray[size].m_Phone = phone;
    cout << "请输入地址" << endl;
    string addr;
    cin >> addr;
    abs->personArray[size].m_Addr = addr;
}

void addPerson(AddressBooks *abs) {
    //判断通讯录是否已满
    if (abs->m_Size == MAX) {
        cout << "通讯录已满,无法添加!" << endl;
        return;
    }
    collectInfo(abs, abs->m_Size);

    //更新 通讯了
    abs->m_Size++;
    cout << "添加成功" << endl;
}


void printPerson(const struct Person *const p) {
    cout << "姓名：" << p->m_Name << "\t";
    cout << "性别：" << (p->m_Sex == 1 ? "男" : "女") << "\t";
    cout << "年龄：" << p->m_Age << "\t";
    cout << "电话：" << p->m_Phone << "\t";
    cout << "地址：" << p->m_Addr << endl;
}

void showPerson(AddressBooks *abs) {
    if (abs->m_Size == 0) {
        cout << "通讯录为空" << endl;
        return;
    }
    for (int i = 0; i < abs->m_Size; i++) {
        printPerson(&abs->personArray[i]);
    }
    cout << endl;
}


//检测联系人是否存在，如果在，返回联系人所在数组中具体位置，不在返回-1
int isExist(AddressBooks *abs, string name) {
    for (int i = 0; i < abs->m_Size; i++) {
        if (abs->personArray[i].m_Name == name) {
            return i;
        }
    }
    return -1;
}

void deletePerson(AddressBooks *abs) {
    cout << "请输入删除联系人姓名" << endl;
    string name;
    cin >> name;
    int pos = isExist(abs, name);
    if (pos == -1) {
        cout << "查无此人" << endl;
        return;
    }
    if (pos != abs->m_Size - 1) {//最后一个，为了好理解，这里不省略了

    } else {
        for (int i = pos; i < abs->m_Size - 1; i++) {
            abs->personArray[pos] = abs->personArray[pos + 1];
        }
    }
    abs->m_Size--;
    cout << "删除成功" << endl;
}

void findPerson(AddressBooks *abs) {
    cout << "请输入查找的联系人姓名" << endl;
    string name;
    cin >> name;
    int pos = isExist(abs, name);
    if (pos == -1) {
        cout << "查无此人" << endl;
        return;
    }
    printPerson(&abs->personArray[pos]);
}

void updatePerson(AddressBooks *abs) {
    cout << "请输入修改的联系人姓名" << endl;
    string name;
    cin >> name;
    int pos = isExist(abs, name);
    if (pos == -1) {
        cout << "查无此人" << endl;
        return;
    }
    collectInfo(abs, pos);
}

void cleanPerson(AddressBooks *abs) {
    abs->m_Size = 0;//做逻辑清空
    cout << "通讯录已经清空" << endl;
}

int main() {
    AddressBooks abs;
    abs.m_Size = 0;
    while (true) {
        showMenu();
        int num = 0;
        cout << "请输入选项" << endl;
        cin >> num;
        switch (num) {
            case 1://添加联系人
                addPerson(&abs);
                break;
            case 2:
                showPerson(&abs);
                break;
            case 3: {
                deletePerson(&abs);
                break;
            }
            case 4:
                findPerson(&abs);
                break;
            case 5:
                updatePerson(&abs);
                break;
            case 6:
                cleanPerson(&abs);
                break;
            case 0:
                cout << "欢迎下次使用" << endl;
                return 0;
        }
    }
    return 0;
}
```