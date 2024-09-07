conda create -n graphrag-ollama-local python=3.10
pip install ollama
pip install future

1. **Clone the repository:**
    
    ```shell
    git clone https://github.com/TheAiSingularity/graphrag-local-ollama.git
    ```
    
2. **Navigate to the repository directory:**
    
    ```shell
    cd graphrag-local-ollama/
    ```
    
3. **Install the graphrag package ** This is the most important step :**
    
    ```shell
    pip install -e .
    ```
    
4. **Create the required input directory: This is where the experiments data and results will be stored - ./ragtest**
    
    ```shell
    mkdir -p ./ragtest/input
    ```
    
5. **Copy sample data folder input/ to ./ragtest. Input/ has the sample data to run the setup. You can add your own data here in .txt format.**
    
    ```shell
    cp input/* ./ragtest/input
    ```
    
6. **Initialize the ./ragtest folder to create the required files:**
    
    ```shell
    python -m graphrag.index --init --root ./ragtest
    ```
    
7. **Move the settings.yaml file, this is the main predefined config file configured with ollama local models :**
    
    ```shell
    cp settings.yaml ./ragtest
    ```

vim ./ragtest/settings.yaml
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072011959.png)
vim ./ragtest/.env
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072011805.png)
python -m graphrag.index --root ./ragtest   运行后，显卡情况如下
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072012163.png)

执行成功
![image.png](https://gitee.com/hxc8/images9/raw/master/img/202409072015473.png)
