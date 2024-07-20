JNATIVE能很方面的调用dll中的方法：

C语言代码：

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/java调用dll/images/6A3267552BF545DD990FA9C60C1A9CF5icon_star.png)

1. #include "stdafx.h"

1. #include   

1. #include   

1. #include   

1. #include   

1. #include    

1. typedef int (*bOpenUsb20Video)();  

1. typedef int (*sGetBarData)(char *out);  

1. typedef int (*bCloseUsb20)();  

1. int main(int argc, char* argv[])  

1. {  

1.     HINSTANCE       hDll;   //DLL句柄 

1.     bOpenUsb20Video DllbOpenUsb20Video;  

1.     sGetBarData     DllsGetBarData;  

1.     bCloseUsb20     DllbCloseUsb20;  

1.     hDll = LoadLibrary("dllLpDecode.dll");  

1. if (hDll != NULL)  

1.     {  

1.         DllbOpenUsb20Video = (bOpenUsb20Video)GetProcAddress(hDll,"bOpenUsb20Video");     

1. if(DllbOpenUsb20Video==NULL)  

1. return0;  

1.         DllsGetBarData = (sGetBarData)GetProcAddress(hDll,"sGetBarData");     

1. if(DllsGetBarData==NULL)  

1. return0;  

1.         DllbCloseUsb20 = (bCloseUsb20)GetProcAddress(hDll,"bCloseUsb20");     

1. if(DllbCloseUsb20==NULL)  

1. return0;      

1.     }     

1. if (0 != DllbOpenUsb20Video ())  

1. return0;  

1. int ret;  

1. char out[256];  

1. int count=10;  

1. while (count)  

1.     {  

1.         ret = DllsGetBarData (out);  

1. if (ret == 1)  

1.         {  

1.             printf ("result:%s\r\n",out);  

1.             count --;  

1.         }  

1.         Sleep (100);  

1.     }  

1.     DllbCloseUsb20 ();  

1.     FreeLibrary(hDll);  

1. return0;  

1. }  





使用jnative改写的方法：

Java代码

![](D:/download/youdaonote-pull-master/data/Technology/JAVA/java调用dll/images/18EFBD4A4591435792D89CDD57965448icon_star.png)

1. /*

1.  * To change this template, choose Tools | Templates

1.  * and open the template in the editor.

1.  */

1. package com.tfsm.movie.camera;  

1. import java.util.logging.Level;  

1. import java.util.logging.Logger;  

1. import org.xvolks.jnative.JNative;  

1. import org.xvolks.jnative.Type;  

1. import org.xvolks.jnative.pointers.Pointer;  

1. import org.xvolks.jnative.pointers.memory.MemoryBlockFactory;  

1. /**

1.  *

1.  * @author Administrator

1.  */

1. publicclass HY100CameraDecoder {  

1. static {  

1. //加载HY100驱动

1.         System.loadLibrary("lib/dllLpDecode");  

1.     }  

1. publicboolean openCamera() {  

1. try {  

1.             JNative openCamera = new JNative("lib/dllLpDecode", "bOpenUsb20Video");  

1.             openCamera.setRetVal(Type.INT);  

1.             openCamera.invoke();  

1. return openCamera.getRetValAsInt() == 0;  

1.         } catch (Exception ex) {  

1.             Logger.getLogger(HY100CameraDecoder.class.getName()).log(Level.SEVERE, null, ex);  

1. returnfalse;  

1.         }  

1.     }  

1. public CameraDecodeResult getDecodeData() {  

1. try {  

1.             JNative decodeData = new JNative("lib/dllLpDecode", "sGetBarData");  

1.             Pointer p = new Pointer(MemoryBlockFactory.createMemoryBlock(4 * 256));  

1.             decodeData.setParameter(0, p);  

1.             decodeData.setRetVal(Type.INT);  

1.             decodeData.invoke();  

1. int resultCode = decodeData.getRetValAsInt();  

1.             CameraDecodeResult reuslt = new CameraDecodeResult();  

1. //为1代表成功

1. if (resultCode == 1) {  

1.                 String result = new String(p.getAsString().getBytes(), "UTF-8");  

1. return reuslt.setSuccess(true).setResult(result);  

1.             }else{  

1. return reuslt;  

1.             }  

1.         } catch (Exception ex) {  

1.             Logger.getLogger(HY100CameraDecoder.class.getName()).log(Level.SEVERE, null, ex);  

1. returnnew CameraDecodeResult();  

1.         }  

1.     }  

1. publicvoid closeCamera() {  

1. try {  

1.             JNative closeCamera = new JNative("lib/dllLpDecode", "bCloseUsb20");  

1.             closeCamera.setRetVal(Type.INT);  

1.             closeCamera.invoke();  

1.         } catch (Exception ex) {  

1.             Logger.getLogger(HY100CameraDecoder.class.getName()).log(Level.SEVERE, null, ex);  

1.         }  

1.     }  

1. }  