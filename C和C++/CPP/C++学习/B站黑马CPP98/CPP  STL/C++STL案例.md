

![](https://gitee.com/hxc8/images3/raw/master/img/202407172233511.jpg)



```javascript
#include "string"
#include "iostream"
#include "vector"
#include "deque"
#include "algorithm"

using namespace std;

/*
 *STL案例 评委打分
 *
 */

class Person {
public:
    Person(string n, int s) {
        name = n;
        score = s;
    }

    string name;
    int score;

};

void createPerson(vector<Person> &v) {
    string nameSeed = "ABCDE";
    for (int i = 0; i < 5; i++) {
        string name = "选手";
        name += nameSeed[i];
        int score = 0;
        Person p(name, score);
        v.push_back(p);
    }
}

void setScore(vector<Person> &v) {
    for (vector<Person>::iterator it = v.begin(); it != v.end(); it++) {
        deque<int> d;
        for (int i = 0; i < 10; i++) {
            int score = rand() % 41 + 60;//60~100
            d.push_back(score);
        }
        cout << "选手：" << it->name << "打分" << endl;
        for (deque<int>::iterator sit = d.begin(); sit != d.end(); sit++) {
            cout << *sit << " ";
        }
        cout << endl;
        //排序
        sort(d.begin(), d.end());
        //去除最低分和最高分
        d.pop_back();
        d.pop_front();
        //取平均分
        int sum = 0;
        for (deque<int>::iterator it2 = d.begin(); it2 != d.end(); it2++) {
            sum = *it2;
        }
        int avg = sum / d.size();
        //将平均分 赋值给选手
        it->score = avg;
    }
}

void printVector(vector<Person> &v) {
    for (vector<Person>::iterator i = v.begin(); i != v.end(); i++) {
        cout << (*i).name << ":" << (*i).score << " ";
    }
    cout << endl;
}

void test() {
//1 创建5名选手
    vector<Person> v;
    createPerson(v);
    printVector(v);

//2 给5名选手打分
    setScore(v);

//3 显示最后得分
    printVector(v);
}

int main() {
    //随机数种子
    srand((unsigned int) time(NULL));
    test();
    return 0;
}
```



```javascript
选手A:0 选手B:0 选手C:0 选手D:0 选手E:0 
选手：选手A打分
73 77 81 70 85 89 64 65 94 60 
选手：选手B打分
96 82 69 87 95 83 100 94 67 65 
选手：选手C打分
68 93 95 73 74 98 97 64 97 99 
选手：选手D打分
88 74 94 99 83 99 61 90 70 64 
选手：选手E打分
60 92 89 72 93 66 67 80 80 94 
选手A:11 选手B:12 选手C:12 选手D:12 选手E:11 
```

