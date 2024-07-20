```


void func1(char dst[N], char src[N])
{
    int size = sizeof(src); // 计算数组大小
    memcpy(dst, src, size);
}
/*
上面代码的问题:
dst和src是以数组名的形式传到函数里面去的,那么它会退化为指针,那么你算出来的size永远等于8(32位下4),那么拷贝就会出现一些意想不到的问题,修改如下:
 */

bool func1_1(char dst[], char src[], int dstlen, int srclen)
{
    assert(dst);
    assert(src);
    if (dstlen <= 0 || srclen <= 0 || dstlen < srclen)
    {
        return false;
    }
    memcpy(dst, src, srclen);
    return true;
}

//------------------------------------------------------------------------------------------------------------------------------------

std::map<uint32_t, time_t>::iterator it;
for (it = fail_agents.begin(); it != fail_agents.end();)
{
    time_t diff = curtime - it->second;
    if (diff > FIX_TIMEOUT)
    {
        fail_agents.erase(it++); // 后置++,限制性erase,再++,erase之后对象都不存在了，再++,导致访问非法内存
    }
    else
    {
        it++;
    }
}

//------------------------------------------------------------------------------------------------------------------------------------

for (; it != end; ++it)
{
    if (ioctl(sock, SIOCGIFFLAGS, &ifr) == 0)
    {
        if (!(irf.ifr_flags & IFF_LOOPBACK))
        {
            if (ioctl(sock, SIOCGIFHWADDR, &ifr) == 0)
            {
                sudccess = 1;
                break;
            }
        }
    }
}
// 上面代码多重if嵌套,我们应该用短路返回的形式,当然理论上时间和答案都是一样的,但是确实能提高性能
for (; it != end; ++it)
{
    if (ioctl(sock, SIOCGIFFLAGS, &ifr) != 0)
        continue;
    if (irf.ifr_flags & IFF_LOOPBACK)
        continue;
    if (ioctl(sock, SIOCGIFHWADDR, &ifr) != 0)
        continue;
    sudccess = 1;
    break;
}


```