- 叫TniL的答道：可以类比CNN中同时使用多个滤波器的作用，直观上讲，多头的注意力有助于网络捕捉到更丰富的特征/信息
且论文中是这么说的：Multi-head attention allows the model to jointly attend to information from different representation subspaces at different positions.
关于different representation subspaces，举一个不一定妥帖的例子：当你浏览网页的时候，你可能在颜色方面更加关注深色的文字，而在字体方面会去注意大的、粗体的文字。这里的颜色和字体就是两个不同的表示子空间。同时关注颜色和字体，可以有效定位到网页中强调的内容。使用多头注意力，也就是综合利用各方面的信息/特征（毕竟，不同的角度有着不同的关注点）

- 叫LooperXX的则答道：在Transformer中使用的多头注意力出现前，基于各种层次的各种fancy的注意力计算方式，层出不穷。而Transformer的多头注意力借鉴了CNN中<font color="#c00000">同一卷积层内使用多个卷积核的思想</font>，原文中使用了 8 个 scaled dot-product attention ，在同一multi-head attention 层中，输入均为 KQV，同时进行注意力的计算，彼此之前参数不共享，最终将结果拼接起来，这样可以允许模型在不同的表示子空间里学习到相关的信息，在此之前的 A Structured Self-attentive Sentence Embedding 也有着类似的思想

简而言之，就是希望<font color="#c00000">每个注意力头，只关注最终输出序列中一个子空间，互相独立</font>，其核心思想在于，抽取到更加丰富的特征信息

                        
原文链接：https://blog.csdn.net/v_JULY_v/article/details/127411638