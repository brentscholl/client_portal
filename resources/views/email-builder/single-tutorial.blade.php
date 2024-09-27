<div>
    <div class="flex space-x-1 text-sm font-medium text-gray-500">
        <x-svg.tutorial class="h-5 w-5"/>
        <span>Single Tutorial</span>
    </div>
    <div class="flex space-x-6 mt-3">
        <div class="flex flex-col space-y-3 w-1/2 flex-grow-1">
            <div>
                <x-input.email-builder.project-select
                    :project="$layout[$i]['inputs']['project']"
                    :projects="$layout[$i]['inputs']['projects']"
                    :i="$i"
                />
            </div>
        </div>
        <div class="flex flex-col space-y-3 w-1/2 flex-grow-1">
            <div>
                <x-input.email-builder.tutorial-select
                    :tutorials="$layout[$i]['inputs']['tutorials']"
                    :tutorialId="$layout[$i]['inputs']['tutorial_id']"
                    :tutorialTitle="$layout[$i]['inputs']['tutorial_title']"
                    :i="$i"
                />
            </div>
        </div>
    </div>
</div>
