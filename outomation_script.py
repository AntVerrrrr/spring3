import pyautogui
import time
import pyperclip
import openai
import queue
import re
import googletrans
import requests
import win32clipboard
import win32con
from io import BytesIO
from PIL import Image

import bs4
import urllib.parse
import openai
import requests
import re
import urllib.request
import cv2
import numpy as np
import googletrans
import queue

openai.api_key = "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"

def ChatGPT_API(user_content):
    messages = [{"role": "user", "content": user_content}]
    completion = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=messages,
        temperature=0.6
    )
    return completion.choices[0].message["content"].strip()


class GPT:
    def __init__(self, user_input):
        self.titleQ_tm = queue.Queue()
        self.user = user_input

        self.titleQ = queue.Queue()
        self.bodyQ = queue.Queue()
        self.tagQ = queue.Queue()
    
    # 테그 생성 메서드
    def create_tag(self):
        teg_gpt = ChatGPT_API(
            f'{self.user}라는 주제에 맞는 단어를 다음 형식에 맞춰서 5개만 추천해줘,'\
            f'단어의 맨 앞에는 항상 ">"가 와야 해 \n'\
            f'>word1\n>word2\n>word3 ...')
        
        # GPT의 답변을 설정한 형식대로 Q버퍼에 저장
        for match in re.finditer(r">(.+?)(\n|$)", teg_gpt):
            tm = match.group(1)
            self.tagQ.put(tm)

        if self.tagQ.empty():
            print("GPT응답 오류")
            exit()
        else:
            print("\n테그 생성 완료")
    
    # 목차 생성 메서드
    def create_content(self):
        title_gpt = ChatGPT_API(
            f'{self.user}라는 주제에 맞게 목차를 다음 형식에 맞춰서 5개만 추천해줘,'\
            f'목차의 맨 앞에는 항상 ">"가 와야 해 \n'\
            f'>목차1\n>목차3\n>목차3 ...')

        for match in re.finditer(r">(.+?)(\n|$)", title_gpt):
            tm = match.group(1)
            self.titleQ.put(tm)
            self.titleQ_tm.put(tm)

        if self.titleQ.empty():
            print("GPT응답 오류")
            exit()
        else:
            print("\n목차 생성 완료")

    # 목차별 내용 생성 메서드
    def create_body(self):
        while not self.titleQ_tm.empty():
            self.bodyQ.put(
                ChatGPT_API(
                    f'{self.user}라는 주제와\n \
                    목차 = ({self.titleQ_tm.get()})에 맞는 대한 100자 정도의 내용을 써줘 .')
                )

class GPT:
   def __init__(self):
      self.tagQ = queue.Queue()
   
   # 
   def create_dalle2_question(self):
      dalle2_question = ChatGPT_API(
         questionFormat()
      )
      # GPT의 답변을 설정한 형식대로 Q버퍼에 저장
      dalle2_question = str(dalle2_question)
      print(f"GPT : \n{assistant_content}")
      print("============================================")
      # GPT가 코멘트를 다는 경우가 있어 그 부분을 제거하기 위한 코드
      assistant_content = assistant_content.split("\n\n")[0]
      print(f"GPT : \n{assistant_content}")
      print("============================================")

      # GPT의 답변을 설정한 형식대로 Q버퍼에 저장
      self.tagQ.put(assistant_content)

      if self.tagQ.empty():
         print("GPT응답 오류")
         exit()
      else:
         print("\ndalle2question 생성 완료")

      return self.tagQ.get()

# 이미지 클립보드에 저장
def send_to_clipboard(clip_type, data):
    win32clipboard.OpenClipboard()
    win32clipboard.EmptyClipboard()
    win32clipboard.SetClipboardData(clip_type, data)
    win32clipboard.CloseClipboard()

def big_image(image_url):
    # 이미지 다운로드
    response = requests.get(image_url)
    img_data = response.content

    # BytesIO를 사용하여 바이트 데이터를 파일처럼 취급
    img_file = BytesIO(img_data)

    # 이미지를 PIL.Image 객체로 변환
    image = Image.open(img_file)

    # 이미지 크기를 2배로 늘리기
    width, height = image.size
    new_width, new_height = width * 2, height * 2
    resized_image = image.resize((new_width, new_height))

    # 이미지를 비트맵 형식으로 변환
    output = BytesIO()
    resized_image.convert("RGB").save(output, "BMP")
    data = output.getvalue()[14:]
    output.close()

    # 클립보드에 이미지 저장
    send_to_clipboard(win32con.CF_DIB, data)
    print("이미지가 클립보드에 저장되었습니다.")


def URLimage_change_output():
   # 이미지 다운로드
   with urllib.request.urlopen(url) as url_response:
      image_array = np.asarray(bytearray(url_response.read()), dtype=np.uint8)
      image = cv2.imdecode(image_array, cv2.IMREAD_COLOR)

   # 원하는 이미지 크기를 지정합니다. (예: 512x512)
   new_width = 512
   new_height = 512

   # 이미지 크기를 조절합니다.
   resized_image = cv2.resize(image, (new_width, new_height))

   # 이미지 출력
   cv2.imshow('Recommended clothes of the day', resized_image)
   cv2.waitKey(0)
   cv2.destroyAllWindows()

class Dalle2:
    def __init__(self, user_in):
        self.translator = googletrans.Translator()
        self.dell2 = user_in

    # 번역 메서드
    def translate(self):
        result1 = self.translator.translate(self.dell2, src='ko', dest='en')
        self.dell2 = result1.text
        print(self.dell2)

    def create_image(self):
        user_pic = f'{self.dell2} realistic 3d images'
        print(user_pic)

        response = openai.Image.create(
            prompt=user_pic,
            n=1,
            size="256x256"
        )
        image_url = response['data'][0]['url']
        print(f"url = \n{image_url}")
        big_image(image_url)

# Dalle2===================
class Dalle2:
   def __init__(self, user_in, user_age, user_s):
      self.dell2 = user_in
      self.user_age = user_age
      self.user_s = user_s

   # 이미지 생성_출력 메서드
   def create_image(self):
      # Dalle2에 입력할 문장 구성
      imagePrompt =   f"{self.dell2} {self.user_age}-year-old {self.user_s} fashion model wearing clothes" 
      print(imagePrompt)

      response = openai.Image.create(
         prompt=imagePrompt,
         n=1,
         size="256x256"
      )
      image_url = response['data'][0]['url']
      print(f"url = \n{image_url}")
      URLimage_change_output(image_url)


def writeKorean(txt, dur):
    pyperclip.copy(txt)
    time.sleep(dur)
    pyautogui.hotkey("ctrl", "v")
    return

for i in range(txt_q.qsize()//2):
    user_input = txt_q.get().strip()
    title = user_input
    dell2 = txt_q.get().strip()
    
    my_GPT = GPT(user_input)
    my_GPT.create_tag()
    my_GPT.create_content()
    my_GPT.create_body()
    
    my_DALLE2 = Dalle2(dell2)
    my_DALLE2.create_image()

    # 글쓰기 열기
    pyautogui.doubleClick(434, 138)
    time.sleep(4)

    # 팝업창 닫기
    pyautogui.doubleClick(662, 193)
    time.sleep(1)

    # 이미지 포스팅
    pyautogui.doubleClick(213, 414)
    pyautogui.hotkey("ctrl", "v")
    time.sleep(3)
    pyautogui.press("enter", interval=0.1)
    time.sleep(0.3)
    pyautogui.press("enter", interval=0.1)

    # 제목 쓰기
    pyautogui.click(92, 263)
    # writeKorean(fr' (AI)', 0.1)
    writeKorean(fr'{title} (AI)', 0.1)
    time.sleep(0.3)
    # 내용 쓰기
    pyautogui.click(151, 712)
    time.sleep(0.3)

    # 소주제 + 내용 포스팅
    while not my_GPT.contentQ.empty():
        line = my_GPT.titleQ.get()
        writeKorean(fr'{line}', 0.1)
        pyautogui.press("enter", interval=0.1)
        writeKorean(fr'{my_GPT.contentQ.get()}', 0.1)
        pyautogui.press("enter", interval=0.1)
        pyautogui.press("enter", interval=0.1)

    pyautogui.press("tab", interval=0.1)
    # 테그 포스팅
    while not my_GPT.tegQ.empty():
        if my_GPT.tegQ.empty():
            break
        teg = my_GPT.tegQ.get()
        time.sleep(0.3)
        writeKorean(fr'{teg}', 0.1)
        pyautogui.press("enter", interval=0.1)
    
    time.sleep(0.3)
    # 발행
    pyautogui.click(858, 995)
    time.sleep(0.3)
    # 공개
    pyautogui.click(129, 692, duration=0.1)
    time.sleep(0.4)
    pyautogui.click(511, 975)
    time.sleep(1)
    #창 닫기
    pyautogui.click(935, 16)

    print("포스팅 종료")

html = requests.get('https://search.naver.com/search.naver?query=날씨')

# HTML 코드 파싱하기
naver_w = bs4.BeautifulSoup(html.text,'html.parser')

# 위치정보 추출
place_tag = naver_w.find('h2', {'class': 'blind'})

place = place_tag.get_text(strip=True)

# 온도 정보 추출하기
lowest = naver_w.find('span', {'class': 'lowest'}).get_text(strip=True)
lowest = re.search(r'-?\d+°', lowest).group()

highest = naver_w.find('span', {'class': 'highest'}).get_text(strip=True)
highest = re.search(r'-?\d+°', highest).group()

temperature_tag = naver_w.find('div', {'class': 'temperature_text'}).strong
temperature = temperature_tag.get_text(strip=True)
temperature = re.search(r'\d+\.\d+°', temperature).group()

# 습도 정보 추출하기
desc_tag = naver_w.find('dd', {'class': 'desc'})
desc = desc_tag.get_text(strip=True)

#미세먼지 정보 추출하기
txt = naver_w.find('span', {'class': 'txt'}).get_text(strip=True)

#강수 정보 추출하기
blind = naver_w.find('span', {'class': 'weather before_slash'}).get_text(strip=True)

user_age = input("나이를 입력하세요 : ")
user_s = input("성별을 숫자로 입력하세요(1.여, 2.남) : ")
if user_s == '1':
   user_s = 'female'
else :
   user_s = 'male'

user_req = input("ex) 출근할 때 입을 옷을 추천해 주세요\n설정할 조건을 알려주세요 : ")

def load_into_queue(filename):
    q = queue.Queue()
    with open(filename, 'r', encoding = 'utf-8') as f:
        for line in f:
            if '\n' != line:
                q.put(line)
    return q

file_path = input("txt파일의 경로를 입력하세요: ")
file_path.replace("\\", "/")

txt_q = load_into_queue(file_path)