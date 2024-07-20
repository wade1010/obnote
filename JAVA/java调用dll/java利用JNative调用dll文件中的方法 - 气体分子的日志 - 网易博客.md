 这几天因为项目需要，用到了java调用dll文件中的方法。写成dll文件大多是用C、VB、Delphi语言弄成的，对于我这个纯粹干java的人员来说，着实让我太为难了。唉，也就是说我编程底子没有搭好，惭愧。要是这些语言我都会些，也不至少让我了解并应用这个技术花了很长时间。

    我是用JNative技术做的一个例子。需要用到的是：JNative.jar,JNative.dll这些是在Windows中开发的，如果用Linus要用到libJNative.so

1.将JNative.dl，所要读的dll文件l放在C:\windows\system32下面。

2.将JNative.jar放在你的项目下的lib下面

3.将要读的dll文件放在跟使用的类相同的目录下。

4.我的类的源码

package sms;

import org.xvolks.jnative.JNative;

import org.xvolks.jnative.exceptions.NativeException;

import org.xvolks.jnative.misc.basicStructures.AbstractBasicData;

import org.xvolks.jnative.pointers.Pointer;

import org.xvolks.jnative.pointers.memory.MemoryBlockFactory;

public class SystemTime extends AbstractBasicData{

    public short wYear;

    public short wMonth;

    public short wDayOfWeek;

    public short wDay;

    public short wHour;

    public short wMinute;

    public short wSecond;

    public short wMilliseconds;

    /**

     * 分配内存，并返回指针

     */

    public Pointer createPointer() throws NativeException {

        pointer = new Pointer(MemoryBlockFactory.createMemoryBlock(getSizeOf()));

        return pointer;

    }

    /**

     * 内存大小

     */

    public int getSizeOf(){

        return 8 * 2;

    }

    /**

     * 获取通过内存指针解析出结果

     */

    public SystemTime getValueFromPointer() throws NativeException {

        wYear = getNextShort();

        wMonth = getNextShort();

        wDayOfWeek = getNextShort();

        wDay = getNextShort();

        wHour = getNextShort();

        wMinute = getNextShort();

        wSecond = getNextShort();

        wMilliseconds = getNextShort();

        return this;

    }

    public SystemTime() throws NativeException{

        super(null);

        createPointer();

    }

    public String toString(){

        return wYear + "/" + wMonth + "/" + wDay + " at + " + wHour + ":" + wMinute + ":" + wSecond + ":" + wMilliseconds;

    }

    public static SystemTime GetSystemTime() throws NativeException, IllegalAccessException {

        // 创建对象

        JNative nGetSystemTime = new JNative("Kernel32.dll", "GetSystemTime");

        SystemTime systemTime = new SystemTime();

        // 设置参数

        nGetSystemTime.setParameter(0, systemTime.getPointer());

        nGetSystemTime.invoke();

        // 解析结构指针内容

        return systemTime.getValueFromPointer();

    }

    public static void main(String[] args) throws NativeException, IllegalAccessException{

        System.err.println(GetSystemTime());

    }

}

   注：Kernel32.dll是我要读的dll文件，GetSystemTime是dll文件中的方法（就是所谓的函数名）

   That's ok,相信我这么不懂dll的都能做出来，你也能

